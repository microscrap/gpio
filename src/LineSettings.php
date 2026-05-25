<?php

namespace Microscrap\Bindings\GPIO;

use Microscrap\Bindings\GPIO\DataObjects\GPIOLineSettings;
use Microscrap\Bindings\GPIO\Enums\LineBias;
use Microscrap\Bindings\GPIO\Enums\LineClock;
use Microscrap\Bindings\GPIO\Enums\LineDirection;
use Microscrap\Bindings\GPIO\Enums\LineDrive;
use Microscrap\Bindings\GPIO\Enums\LineEdge;
use Microscrap\Bindings\GPIO\Enums\LineValue;

class LineSettings
{
    public static function gpioLineSettingsNew(): GPIOLineSettings
    {
        return new GPIOLineSettings();
    }

    public static function gpioLineSettingsReset(GPIOLineSettings $settings): void
    {
        $defaults = new GPIOLineSettings();

        $settings->direction = $defaults->direction;
        $settings->edge_detection = $defaults->edge_detection;
        $settings->drive = $defaults->drive;
        $settings->bias = $defaults->bias;
        $settings->active_low = $defaults->active_low;
        $settings->event_clock = $defaults->event_clock;
        $settings->debounce_period_us = $defaults->debounce_period_us;
        $settings->output_value = $defaults->output_value;
    }

    public static function gpioLineSettingsCopy(GPIOLineSettings $settings): GPIOLineSettings
    {
        return clone $settings;
    }

    public static function gpioLineSettingsSetDirection(GPIOLineSettings $settings, LineDirection|int $direction): int
    {
        $resolved = $direction instanceof LineDirection ? $direction : LineDirection::tryFrom($direction);
        if (! $resolved instanceof LineDirection) {
            return -1;
        }

        $settings->direction = $resolved;

        return 0;
    }

    public static function gpioLineSettingsGetDirection(GPIOLineSettings $settings): LineDirection
    {
        return $settings->direction;
    }

    public static function gpioLineSettingsSetEdgeDetection(GPIOLineSettings $settings, LineEdge|int $edge): int
    {
        $resolved = $edge instanceof LineEdge ? $edge : LineEdge::tryFrom($edge);
        if (! $resolved instanceof LineEdge) {
            return -1;
        }

        $settings->edge_detection = $resolved;

        return 0;
    }

    public static function gpioLineSettingsGetEdgeDetection(GPIOLineSettings $settings): LineEdge
    {
        return $settings->edge_detection;
    }

    public static function gpioLineSettingsSetBias(GPIOLineSettings $settings, LineBias|int $bias): int
    {
        $resolved = $bias instanceof LineBias ? $bias : LineBias::tryFrom($bias);
        if (! $resolved instanceof LineBias) {
            return -1;
        }

        $settings->bias = $resolved;

        return 0;
    }

    public static function gpioLineSettingsGetBias(GPIOLineSettings $settings): LineBias
    {
        return $settings->bias;
    }

    public static function gpioLineSettingsSetDrive(GPIOLineSettings $settings, LineDrive|int $drive): int
    {
        $resolved = $drive instanceof LineDrive ? $drive : LineDrive::tryFrom($drive);
        if (! $resolved instanceof LineDrive) {
            return -1;
        }

        $settings->drive = $resolved;

        return 0;
    }

    public static function gpioLineSettingsGetDrive(GPIOLineSettings $settings): LineDrive
    {
        return $settings->drive;
    }

    public static function gpioLineSettingsSetActiveLow(GPIOLineSettings $settings, bool $active_low): int
    {
        $settings->active_low = $active_low;

        return 0;
    }

    public static function gpioLineSettingsGetActiveLow(GPIOLineSettings $settings): bool
    {
        return $settings->active_low;
    }

    public static function gpioLineSettingsSetDebouncePeriodUs(GPIOLineSettings $settings, int $period_us): int
    {
        if ($period_us < 0) {
            return -1;
        }

        $settings->debounce_period_us = $period_us;

        return 0;
    }

    public static function gpioLineSettingsGetDebouncePeriodUs(GPIOLineSettings $settings): int
    {
        return $settings->debounce_period_us;
    }

    public static function gpioLineSettingsSetEventClock(GPIOLineSettings $settings, LineClock|int $event_clock): int
    {
        $resolved = $event_clock instanceof LineClock ? $event_clock : LineClock::tryFrom($event_clock);
        if (! $resolved instanceof LineClock) {
            return -1;
        }

        $settings->event_clock = $resolved;

        return 0;
    }

    public static function gpioLineSettingsGetEventClock(GPIOLineSettings $settings): LineClock
    {
        return $settings->event_clock;
    }

    public static function gpioLineSettingsSetOutputValue(GPIOLineSettings $settings, LineValue|int $output_value): int
    {
        $resolved = $output_value instanceof LineValue ? $output_value : LineValue::tryFrom($output_value);
        if (! $resolved instanceof LineValue || $resolved === LineValue::Error) {
            return -1;
        }

        $settings->output_value = $resolved;

        return 0;
    }

    public static function gpioLineSettingsGetOutputValue(GPIOLineSettings $settings): LineValue
    {
        return $settings->output_value;
    }
}
