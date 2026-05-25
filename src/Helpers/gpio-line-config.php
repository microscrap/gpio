<?php

use Microscrap\Bindings\GPIO\DataObjects\GPIOLineConfig;
use Microscrap\Bindings\GPIO\DataObjects\GPIOLineSettings;
use Microscrap\Bindings\GPIO\DataObjects\GPIOV2LineConfig;
use Microscrap\Bindings\GPIO\Enums\LineValue;
use Microscrap\Bindings\GPIO\LineConfig;

if(!function_exists('gpiod_line_config_new'))
{
    function gpiod_line_config_new(): GPIOLineConfig
    {
        return LineConfig::gpioLineConfigNew();
    }
}

if(!function_exists('gpiod_line_config_reset'))
{
    function gpiod_line_config_reset(GPIOLineConfig $config): void
    {
        LineConfig::gpioLineConfigReset($config);
    }
}

if(!function_exists('gpiod_line_config_add_line_settings'))
{
    /**
     * @param array<int, int> $offsets
     */
    function gpiod_line_config_add_line_settings(
        GPIOLineConfig $config,
        array $offsets,
        ?GPIOLineSettings $settings = null,
    ): int {
        return LineConfig::gpioLineConfigAddLineSettings($config, $offsets, $settings);
    }
}

if(!function_exists('gpiod_line_config_get_line_settings'))
{
    function gpiod_line_config_get_line_settings(GPIOLineConfig $config, int $offset): ?GPIOLineSettings
    {
        return LineConfig::gpioLineConfigGetLineSettings($config, $offset);
    }
}

if(!function_exists('gpiod_line_config_set_output_values'))
{
    /**
     * @param array<int, LineValue|int> $values
     */
    function gpiod_line_config_set_output_values(GPIOLineConfig $config, array $values): int
    {
        return LineConfig::gpioLineConfigSetOutputValues($config, $values);
    }
}

if(!function_exists('gpiod_line_config_get_num_configured_offsets'))
{
    function gpiod_line_config_get_num_configured_offsets(GPIOLineConfig $config): int
    {
        return LineConfig::gpioLineConfigGetNumConfiguredOffsets($config);
    }
}

if(!function_exists('gpiod_line_config_get_configured_offsets'))
{
    /**
     * @return array<int, int>
     */
    function gpiod_line_config_get_configured_offsets(GPIOLineConfig $config, int $max_offsets): array
    {
        return LineConfig::gpioLineConfigGetConfiguredOffsets($config, $max_offsets);
    }
}

if(!function_exists('gpiod_line_config_to_uapi'))
{
    function gpiod_line_config_to_uapi(GPIOLineConfig $config): ?GPIOV2LineConfig
    {
        return LineConfig::toUapi($config);
    }
}
