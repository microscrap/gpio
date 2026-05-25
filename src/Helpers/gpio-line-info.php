<?php

use Microscrap\Bindings\GPIO\LineInfo;
use Microscrap\Bindings\GPIO\DataObjects\GPIOLineInfo;
use Microscrap\Bindings\GPIO\DataObjects\GPIOV2LineInfo;
use Microscrap\Bindings\GPIO\Enums\LineBias;
use Microscrap\Bindings\GPIO\Enums\LineClock;
use Microscrap\Bindings\GPIO\Enums\LineDirection;
use Microscrap\Bindings\GPIO\Enums\LineDrive;
use Microscrap\Bindings\GPIO\Enums\LineEdge;

if(!function_exists('gpiod_line_info_from_uapi'))
{
    function gpiod_line_info_from_uapi(GPIOV2LineInfo &$uapi_info): ?GPIOLineInfo
    {
        return LineInfo::GpioLineInfoFromUapi($uapi_info);
    }
}

if(!function_exists('gpiod_line_info_copy'))
{
    function gpiod_line_info_copy(GPIOLineInfo $info): GPIOLineInfo
    {
        return LineInfo::gpioLineInfoCopy($info);
    }
}

if(!function_exists('gpiod_line_info_get_offset'))
{
    function gpiod_line_info_get_offset(GPIOLineInfo $info): int
    {
        return LineInfo::gpioLineInfoGetOffset($info);
    }
}

if(!function_exists('gpiod_line_info_get_name'))
{
    function gpiod_line_info_get_name(GPIOLineInfo $info): string
    {
        return LineInfo::gpioLineInfoGetName($info);
    }
}

if(!function_exists('gpiod_line_info_is_used'))
{
    function gpiod_line_info_is_used(GPIOLineInfo $info): bool
    {
        return LineInfo::gpioLineInfoIsUsed($info);
    }
}

if(!function_exists('gpiod_line_info_get_consumer'))
{
    function gpiod_line_info_get_consumer(GPIOLineInfo $info): string
    {
        return LineInfo::gpioLineInfoGetConsumer($info);
    }
}

if(!function_exists('gpiod_line_info_get_direction'))
{
    function gpiod_line_info_get_direction(GPIOLineInfo $info): LineDirection
    {
        return LineInfo::gpioLineInfoGetDirection($info);
    }
}

if(!function_exists('gpiod_line_info_get_edge_detection'))
{
    function gpiod_line_info_get_edge_detection(GPIOLineInfo $info): LineEdge
    {
        return LineInfo::gpioLineInfoGetEdgeDetection($info);
    }
}

if(!function_exists('gpiod_line_info_get_bias'))
{
    function gpiod_line_info_get_bias(GPIOLineInfo $info): LineBias
    {
        return LineInfo::gpioLineInfoGetBias($info);
    }
}

if(!function_exists('gpiod_line_info_get_drive'))
{
    function gpiod_line_info_get_drive(GPIOLineInfo $info): LineDrive
    {
        return LineInfo::gpioLineInfoGetDrive($info);
    }
}

if(!function_exists('gpiod_line_info_is_active_low'))
{
    function gpiod_line_info_is_active_low(GPIOLineInfo $info): bool
    {
        return LineInfo::gpioLineInfoIsActiveLow($info);
    }
}

if(!function_exists('gpiod_line_info_is_debounced'))
{
    function gpiod_line_info_is_debounced(GPIOLineInfo $info): bool
    {
        return LineInfo::gpioLineInfoIsDebounced($info);
    }
}

if(!function_exists('gpiod_line_info_get_debounce_period_us'))
{
    function gpiod_line_info_get_debounce_period_us(GPIOLineInfo $info): int
    {
        return LineInfo::gpioLineInfoGetDebouncePeriodUs($info);
    }
}

if(!function_exists('gpiod_line_info_get_event_clock'))
{
    function gpiod_line_info_get_event_clock(GPIOLineInfo $info): LineClock
    {
        return LineInfo::gpioLineInfoGetEventClock($info);
    }
}
