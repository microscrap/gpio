<?php

namespace Microscrap\Bindings\GPIO;

use Microscrap\Bindings\GPIO\DataObjects\GPIOLineConfig;
use Microscrap\Bindings\GPIO\DataObjects\GPIOLineSettings;
use Microscrap\Bindings\GPIO\DataObjects\GPIOPerLineConfig;
use Microscrap\Bindings\GPIO\DataObjects\GPIOSettingsNode;
use Microscrap\Bindings\GPIO\DataObjects\GPIOV2LineConfig;
use Microscrap\Bindings\GPIO\DataObjects\GPIOV2LineConfigAttribute;
use Microscrap\Bindings\GPIO\Enums\GPIOV2LineAttrId;
use Microscrap\Bindings\GPIO\Enums\GPIOV2LineFlag;
use Microscrap\Bindings\GPIO\Enums\LineBias;
use Microscrap\Bindings\GPIO\Enums\LineClock;
use Microscrap\Bindings\GPIO\Enums\LineDirection;
use Microscrap\Bindings\GPIO\Enums\LineDrive;
use Microscrap\Bindings\GPIO\Enums\LineEdge;
use Microscrap\Bindings\GPIO\Enums\LineValue;

class LineConfig
{
    public static function gpioLineConfigNew(): GPIOLineConfig
    {
        return new GPIOLineConfig();
    }

    public static function gpioLineConfigReset(GPIOLineConfig $config): void
    {
        static::freeRefs($config);
        $config->num_configs = 0;
        $config->num_output_values = 0;
        $config->sref_list = null;

        for ($index = 0; $index < 64; $index++) { // GPIO_V2_LINES_MAX
            $config->line_configs[$index]->offset = 0;
            $config->line_configs[$index]->node = null;
            $config->output_values[$index] = LineValue::Inactive;
        }
    }

    /**
     * @param array<int, int> $offsets
     */
    public static function gpioLineConfigAddLineSettings(
        GPIOLineConfig $config,
        array $offsets,
        ?GPIOLineSettings $settings,
    ): int {
        $num_offsets = count($offsets);
        if ($num_offsets === 0 || ($config->num_configs + $num_offsets) > 64) { // GPIO_V2_LINES_MAX
            return -1;
        }

        foreach ($offsets as $offset) {
            if (! is_int($offset) || $offset < 0) {
                return -1;
            }
        }

        $copied_settings = $settings instanceof GPIOLineSettings
            ? gpiod_line_settings_copy($settings)
            : gpiod_line_settings_new();

        if (! $copied_settings instanceof GPIOLineSettings) {
            return -1;
        }

        $node = new GPIOSettingsNode($copied_settings);
        $node->refcnt = 0;
        $node->next = $config->sref_list;
        if ($config->sref_list instanceof GPIOSettingsNode) {
            $config->sref_list->prev = $node;
        }
        $node->prev = null;
        $config->sref_list = $node;

        foreach ($offsets as $offset) {
            $per_line = static::findConfig($config, $offset);
            $node->refcnt++;
            $per_line->offset = $offset;

            $old = $per_line->node;
            $per_line->node = $node;

            if ($old instanceof GPIOSettingsNode) {
                $old->refcnt--;
                if ($old->refcnt <= 0) {
                    static::unlinkSettingsNode($config, $old);
                }
            }
        }

        return 0;
    }

    public static function gpioLineConfigGetLineSettings(GPIOLineConfig $config, int $offset): ?GPIOLineSettings
    {
        for ($index = 0; $index < $config->num_configs; $index++) {
            $per_line = $config->line_configs[$index];
            if ($per_line->offset !== $offset || ! $per_line->node instanceof GPIOSettingsNode) {
                continue;
            }

            $settings = gpiod_line_settings_copy($per_line->node->settings);
            if (! $settings instanceof GPIOLineSettings) {
                return null;
            }

            /*
             * Match libgpiod: global output values override per-line settings
             * by configuration index.
             */
            if ($config->num_output_values > $index) {
                $ret = gpiod_line_settings_set_output_value($settings, $config->output_values[$index]);
                if ($ret !== 0) {
                    return null;
                }
            }

            return $settings;
        }

        return null;
    }

    /**
     * @param array<int, LineValue|int> $values
     */
    public static function gpioLineConfigSetOutputValues(GPIOLineConfig $config, array $values): int
    {
        $normalized = array_values($values);
        $num_values = count($normalized);
        if ($num_values === 0 || $num_values > 64) { // GPIO_V2_LINES_MAX
            return -1;
        }

        foreach ($normalized as $index => $value) {
            $resolved = $value instanceof LineValue ? $value : LineValue::tryFrom($value);
            if (! $resolved instanceof LineValue || $resolved === LineValue::Error) {
                $config->num_output_values = 0;

                return -1;
            }

            $config->output_values[$index] = $resolved;
        }

        $config->num_output_values = $num_values;

        return 0;
    }

    public static function gpioLineConfigGetNumConfiguredOffsets(GPIOLineConfig $config): int
    {
        return $config->num_configs;
    }

    /**
     * @return array<int, int>
     */
    public static function gpioLineConfigGetConfiguredOffsets(GPIOLineConfig $config, int $max_offsets): array
    {
        if ($max_offsets <= 0 || $config->num_configs === 0) {
            return [];
        }

        $num_offsets = min($config->num_configs, $max_offsets);
        $offsets = [];

        for ($index = 0; $index < $num_offsets; $index++) {
            $offsets[] = $config->line_configs[$index]->offset;
        }

        return $offsets;
    }

    public static function toUapi(GPIOLineConfig $config): ?GPIOV2LineConfig
    {
        $attrs = [];
        $flags = 0;

        if (static::hasAtLeastOneOutputDirection($config)) {
            $output_values_attr = static::buildOutputValuesAttr($config);
            if ($output_values_attr instanceof GPIOV2LineConfigAttribute) {
                $attrs[] = $output_values_attr;
            }
        }

        $ret = static::appendDebouncePeriodAttrs($config, $attrs);
        if ($ret !== 0) {
            return null;
        }

        $ret = static::setFlagDefaultsAndAttrs($config, $flags, $attrs);
        if ($ret !== 0) {
            return null;
        }

        return new GPIOV2LineConfig(
            flags: $flags,
            num_attrs: count($attrs),
            attrs: $attrs,
        );
    }

    private static function findConfig(GPIOLineConfig $config, int $offset): GPIOPerLineConfig
    {
        for ($index = 0; $index < $config->num_configs; $index++) {
            $per_line = $config->line_configs[$index];

            if ($offset === $per_line->offset) {
                return $per_line;
            }
        }

        $per_line = $config->line_configs[$config->num_configs];
        $config->num_configs++;

        return $per_line;
    }

    private static function freeRefs(GPIOLineConfig $config): void
    {
        $node = $config->sref_list;

        while ($node instanceof GPIOSettingsNode) {
            $next = $node->next;
            $node->next = null;
            $node->prev = null;
            $node = $next;
        }
    }

    private static function unlinkSettingsNode(GPIOLineConfig $config, GPIOSettingsNode $node): void
    {
        if ($node->prev instanceof GPIOSettingsNode) {
            $node->prev->next = $node->next;
        }

        if ($node->next instanceof GPIOSettingsNode) {
            $node->next->prev = $node->prev;
        }

        if ($config->sref_list === $node) {
            $config->sref_list = $node->next;
        }

        $node->next = null;
        $node->prev = null;
    }

    private static function hasAtLeastOneOutputDirection(GPIOLineConfig $config): bool
    {
        for ($index = 0; $index < $config->num_configs; $index++) {
            $settings = $config->line_configs[$index]->node?->settings;
            if (! $settings instanceof GPIOLineSettings) {
                continue;
            }

            if (gpiod_line_settings_get_direction($settings) === LineDirection::Output) {
                return true;
            }
        }

        return false;
    }

    private static function buildOutputValuesAttr(GPIOLineConfig $config): ?GPIOV2LineConfigAttribute
    {
        $mask = 0;
        $values = 0;

        for ($index = 0; $index < $config->num_configs; $index++) {
            $settings = $config->line_configs[$index]->node?->settings;
            if (! $settings instanceof GPIOLineSettings) {
                continue;
            }

            if (gpiod_line_settings_get_direction($settings) !== LineDirection::Output) {
                continue;
            }

            $mask = static::assignMaskBit($mask, $index, true);
            $values = static::assignMaskBit(
                $values,
                $index,
                gpiod_line_settings_get_output_value($settings) === LineValue::Active,
            );
        }

        for ($index = 0; $index < $config->num_output_values; $index++) {
            $mask = static::assignMaskBit($mask, $index, true);
            $values = static::assignMaskBit(
                $values,
                $index,
                $config->output_values[$index] === LineValue::Active,
            );
        }

        return new GPIOV2LineConfigAttribute(
            id: GPIOV2LineAttrId::OUTPUT_VALUES,
            mask: $mask,
            values: $values,
        );
    }

    /**
     * @param array<int, GPIOV2LineConfigAttribute> $attrs
     */
    private static function appendDebouncePeriodAttrs(GPIOLineConfig $config, array &$attrs): int
    {
        $done = 0;

        for ($index = 0; $index < $config->num_configs; $index++) {
            if (static::isMaskBitSet($done, $index)) {
                continue;
            }

            $done = static::assignMaskBit($done, $index, true);

            $settings_i = $config->line_configs[$index]->node?->settings;
            if (! $settings_i instanceof GPIOLineSettings) {
                continue;
            }

            $period_i = gpiod_line_settings_get_debounce_period_us($settings_i);
            if ($period_i === 0) {
                continue;
            }

            if (count($attrs) === 10) { // GPIO_V2_LINE_NUM_ATTRS_MAX
                return -1;
            }

            $mask = static::assignMaskBit(0, $index, true);

            for ($other_index = $index; $other_index < $config->num_configs; $other_index++) {
                $settings_j = $config->line_configs[$other_index]->node?->settings;
                if (! $settings_j instanceof GPIOLineSettings) {
                    continue;
                }

                $period_j = gpiod_line_settings_get_debounce_period_us($settings_j);
                if ($period_i === $period_j) {
                    $mask = static::assignMaskBit($mask, $other_index, true);
                    $done = static::assignMaskBit($done, $other_index, true);
                }
            }

            $attrs[] = new GPIOV2LineConfigAttribute(
                id: GPIOV2LineAttrId::DEBOUNCE,
                mask: $mask,
                debounce_period_us: $period_i,
            );
        }

        return 0;
    }

    /**
     * @param array<int, GPIOV2LineConfigAttribute> $attrs
     */
    private static function setFlagDefaultsAndAttrs(GPIOLineConfig $config, int &$flags, array &$attrs): int
    {
        $done = 0;
        $globals_taken = false;

        for ($index = 0; $index < $config->num_configs; $index++) {
            if (static::isMaskBitSet($done, $index)) {
                continue;
            }

            $done = static::assignMaskBit($done, $index, true);

            $settings_i = $config->line_configs[$index]->node?->settings;
            if (! $settings_i instanceof GPIOLineSettings) {
                continue;
            }

            if (! $globals_taken) {
                $globals_taken = true;
                $flags = static::makeKernelFlags($settings_i);

                for ($other_index = $index; $other_index < $config->num_configs; $other_index++) {
                    $settings_j = $config->line_configs[$other_index]->node?->settings;
                    if (! $settings_j instanceof GPIOLineSettings) {
                        continue;
                    }

                    if (static::settingsEqual($settings_i, $settings_j)) {
                        $done = static::assignMaskBit($done, $other_index, true);
                    }
                }
            } else {
                if (count($attrs) === 10) { // GPIO_V2_LINE_NUM_ATTRS_MAX
                    return -1;
                }

                $mask = static::assignMaskBit(0, $index, true);
                $attr_flags = static::makeKernelFlags($settings_i);

                for ($other_index = $index; $other_index < $config->num_configs; $other_index++) {
                    $settings_j = $config->line_configs[$other_index]->node?->settings;
                    if (! $settings_j instanceof GPIOLineSettings) {
                        continue;
                    }

                    if (static::settingsEqual($settings_i, $settings_j)) {
                        $done = static::assignMaskBit($done, $other_index, true);
                        $mask = static::assignMaskBit($mask, $other_index, true);
                    }
                }

                $attrs[] = new GPIOV2LineConfigAttribute(
                    id: GPIOV2LineAttrId::FLAGS,
                    mask: $mask,
                    flags: $attr_flags,
                );
            }
        }

        return 0;
    }

    private static function makeKernelFlags(GPIOLineSettings $settings): int
    {
        $flags = 0;

        switch (gpiod_line_settings_get_direction($settings)) {
            case LineDirection::Input:
                $flags |= GPIOV2LineFlag::INPUT->value;
                break;
            case LineDirection::Output:
                $flags |= GPIOV2LineFlag::OUTPUT->value;
                break;
            default:
                break;
        }

        switch (gpiod_line_settings_get_edge_detection($settings)) {
            case LineEdge::Falling:
                $flags |= GPIOV2LineFlag::EDGE_FALLING->value | GPIOV2LineFlag::INPUT->value;
                break;
            case LineEdge::Rising:
                $flags |= GPIOV2LineFlag::EDGE_RISING->value | GPIOV2LineFlag::INPUT->value;
                break;
            case LineEdge::Both:
                $flags |= GPIOV2LineFlag::EDGE_FALLING->value
                    | GPIOV2LineFlag::EDGE_RISING->value
                    | GPIOV2LineFlag::INPUT->value;
                break;
            default:
                break;
        }

        switch (gpiod_line_settings_get_drive($settings)) {
            case LineDrive::OpenDrain:
                $flags |= GPIOV2LineFlag::OPEN_DRAIN->value;
                break;
            case LineDrive::OpenSource:
                $flags |= GPIOV2LineFlag::OPEN_SOURCE->value;
                break;
            default:
                break;
        }

        switch (gpiod_line_settings_get_bias($settings)) {
            case LineBias::Disabled:
                $flags |= GPIOV2LineFlag::BIAS_DISABLED->value;
                break;
            case LineBias::PullUp:
                $flags |= GPIOV2LineFlag::BIAS_PULL_UP->value;
                break;
            case LineBias::PullDown:
                $flags |= GPIOV2LineFlag::BIAS_PULL_DOWN->value;
                break;
            default:
                break;
        }

        if (gpiod_line_settings_get_active_low($settings)) {
            $flags |= GPIOV2LineFlag::ACTIVE_LOW->value;
        }

        switch (gpiod_line_settings_get_event_clock($settings)) {
            case LineClock::Realtime:
                $flags |= GPIOV2LineFlag::EVENT_CLOCK_REALTIME->value;
                break;
            case LineClock::Hte:
                $flags |= GPIOV2LineFlag::EVENT_CLOCK_HTE->value;
                break;
            default:
                break;
        }

        return $flags;
    }

    private static function settingsEqual(GPIOLineSettings $left, GPIOLineSettings $right): bool
    {
        if (gpiod_line_settings_get_direction($left) !== gpiod_line_settings_get_direction($right)) {
            return false;
        }

        if (gpiod_line_settings_get_edge_detection($left) !== gpiod_line_settings_get_edge_detection($right)) {
            return false;
        }

        if (gpiod_line_settings_get_bias($left) !== gpiod_line_settings_get_bias($right)) {
            return false;
        }

        if (gpiod_line_settings_get_drive($left) !== gpiod_line_settings_get_drive($right)) {
            return false;
        }

        if (gpiod_line_settings_get_active_low($left) !== gpiod_line_settings_get_active_low($right)) {
            return false;
        }

        if (gpiod_line_settings_get_event_clock($left) !== gpiod_line_settings_get_event_clock($right)) {
            return false;
        }

        return true;
    }

    private static function assignMaskBit(int $value, int $bit, bool $state): int
    {
        $mask = static::bitMask($bit);

        if ($state) {
            return $value | $mask;
        }

        return $value & (~$mask);
    }

    private static function isMaskBitSet(int $value, int $bit): bool
    {
        return (bool) ($value & static::bitMask($bit));
    }

    private static function bitMask(int $bit): int
    {
        return $bit === 63
            ? PHP_INT_MIN
            : (1 << $bit);
    }
}
