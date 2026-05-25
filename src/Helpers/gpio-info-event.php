<?php

use Microscrap\Bindings\GPIO\DataObjects\GPIOInfoEvent;
use Microscrap\Bindings\GPIO\DataObjects\GPIOLineInfo;
use Microscrap\Bindings\GPIO\Enums\InfoEventType;
use Microscrap\Bindings\GPIO\InfoEvent;

if(!function_exists('gpiod_info_event_get_event_type'))
{
    function gpiod_info_event_get_event_type(GPIOInfoEvent $event): InfoEventType
    {
        return InfoEvent::gpioInfoEventGetEventType($event);
    }
}

if(!function_exists('gpiod_info_event_get_timestamp_ns'))
{
    function gpiod_info_event_get_timestamp_ns(GPIOInfoEvent $event): int
    {
        return InfoEvent::gpioInfoEventGetTimestampNs($event);
    }
}

if(!function_exists('gpiod_info_event_get_line_info'))
{
    function gpiod_info_event_get_line_info(GPIOInfoEvent $event): GPIOLineInfo
    {
        return InfoEvent::gpioInfoEventGetLineInfo($event);
    }
}
