<?php

use Microscrap\Bindings\GPIO\DataObjects\GPIOLineSettings;
use Microscrap\Bindings\GPIO\Enums\LineBias;
use Microscrap\Bindings\GPIO\Enums\LineClock;
use Microscrap\Bindings\GPIO\Enums\LineDirection;
use Microscrap\Bindings\GPIO\Enums\LineDrive;
use Microscrap\Bindings\GPIO\Enums\LineEdge;
use Microscrap\Bindings\GPIO\Enums\LineValue;
use Microscrap\Bindings\GPIO\LineSettings;

if(!function_exists('gpiod_line_settings_new'))
{
    function gpiod_line_settings_new(): GPIOLineSettings
    {
        return LineSettings::gpioLineSettingsNew();
    }
}

if(!function_exists('gpiod_line_settings_reset'))
{
    function gpiod_line_settings_reset(GPIOLineSettings $settings): void
    {
        LineSettings::gpioLineSettingsReset($settings);
    }
}

if(!function_exists('gpiod_line_settings_copy'))
{
    function gpiod_line_settings_copy(GPIOLineSettings $settings): GPIOLineSettings
    {
        return LineSettings::gpioLineSettingsCopy($settings);
    }
}

if(!function_exists('gpiod_line_settings_set_direction'))
{
    function gpiod_line_settings_set_direction(GPIOLineSettings $settings, LineDirection|int $direction): int
    {
        return LineSettings::gpioLineSettingsSetDirection($settings, $direction);
    }
}

if(!function_exists('gpiod_line_settings_get_direction'))
{
    function gpiod_line_settings_get_direction(GPIOLineSettings $settings): LineDirection
    {
        return LineSettings::gpioLineSettingsGetDirection($settings);
    }
}

if(!function_exists('gpiod_line_settings_set_edge_detection'))
{
    function gpiod_line_settings_set_edge_detection(GPIOLineSettings $settings, LineEdge|int $edge): int
    {
        return LineSettings::gpioLineSettingsSetEdgeDetection($settings, $edge);
    }
}

if(!function_exists('gpiod_line_settings_get_edge_detection'))
{
    function gpiod_line_settings_get_edge_detection(GPIOLineSettings $settings): LineEdge
    {
        return LineSettings::gpioLineSettingsGetEdgeDetection($settings);
    }
}

if(!function_exists('gpiod_line_settings_set_bias'))
{
    function gpiod_line_settings_set_bias(GPIOLineSettings $settings, LineBias|int $bias): int
    {
        return LineSettings::gpioLineSettingsSetBias($settings, $bias);
    }
}

if(!function_exists('gpiod_line_settings_get_bias'))
{
    function gpiod_line_settings_get_bias(GPIOLineSettings $settings): LineBias
    {
        return LineSettings::gpioLineSettingsGetBias($settings);
    }
}

if(!function_exists('gpiod_line_settings_set_drive'))
{
    function gpiod_line_settings_set_drive(GPIOLineSettings $settings, LineDrive|int $drive): int
    {
        return LineSettings::gpioLineSettingsSetDrive($settings, $drive);
    }
}

if(!function_exists('gpiod_line_settings_get_drive'))
{
    function gpiod_line_settings_get_drive(GPIOLineSettings $settings): LineDrive
    {
        return LineSettings::gpioLineSettingsGetDrive($settings);
    }
}

if(!function_exists('gpiod_line_settings_set_active_low'))
{
    function gpiod_line_settings_set_active_low(GPIOLineSettings $settings, bool $active_low): int
    {
        return LineSettings::gpioLineSettingsSetActiveLow($settings, $active_low);
    }
}

if(!function_exists('gpiod_line_settings_get_active_low'))
{
    function gpiod_line_settings_get_active_low(GPIOLineSettings $settings): bool
    {
        return LineSettings::gpioLineSettingsGetActiveLow($settings);
    }
}

if(!function_exists('gpiod_line_settings_set_debounce_period_us'))
{
    function gpiod_line_settings_set_debounce_period_us(GPIOLineSettings $settings, int $period_us): int
    {
        return LineSettings::gpioLineSettingsSetDebouncePeriodUs($settings, $period_us);
    }
}

if(!function_exists('gpiod_line_settings_get_debounce_period_us'))
{
    function gpiod_line_settings_get_debounce_period_us(GPIOLineSettings $settings): int
    {
        return LineSettings::gpioLineSettingsGetDebouncePeriodUs($settings);
    }
}

if(!function_exists('gpiod_line_settings_set_event_clock'))
{
    function gpiod_line_settings_set_event_clock(GPIOLineSettings $settings, LineClock|int $event_clock): int
    {
        return LineSettings::gpioLineSettingsSetEventClock($settings, $event_clock);
    }
}

if(!function_exists('gpiod_line_settings_get_event_clock'))
{
    function gpiod_line_settings_get_event_clock(GPIOLineSettings $settings): LineClock
    {
        return LineSettings::gpioLineSettingsGetEventClock($settings);
    }
}

if(!function_exists('gpiod_line_settings_set_output_value'))
{
    function gpiod_line_settings_set_output_value(GPIOLineSettings $settings, LineValue|int $output_value): int
    {
        return LineSettings::gpioLineSettingsSetOutputValue($settings, $output_value);
    }
}

if(!function_exists('gpiod_line_settings_get_output_value'))
{
    function gpiod_line_settings_get_output_value(GPIOLineSettings $settings): LineValue
    {
        return LineSettings::gpioLineSettingsGetOutputValue($settings);
    }
}
