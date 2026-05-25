<?php

use Microscrap\Bindings\GPIO\DataObjects\GPIOEdgeEvent;
use Microscrap\Bindings\GPIO\EdgeEvent;
use Microscrap\Bindings\GPIO\Enums\EdgeEventType;

if(!function_exists('gpiod_edge_event_copy'))
{
    function gpiod_edge_event_copy(GPIOEdgeEvent $event): GPIOEdgeEvent
    {
        return EdgeEvent::gpioEdgeEventCopy($event);
    }
}

if(!function_exists('gpiod_edge_event_get_event_type'))
{
    function gpiod_edge_event_get_event_type(GPIOEdgeEvent $event): EdgeEventType
    {
        return EdgeEvent::gpioEdgeEventGetEventType($event);
    }
}

if(!function_exists('gpiod_edge_event_get_timestamp_ns'))
{
    function gpiod_edge_event_get_timestamp_ns(GPIOEdgeEvent $event): int
    {
        return EdgeEvent::gpioEdgeEventGetTimestampNs($event);
    }
}

if(!function_exists('gpiod_edge_event_get_line_offset'))
{
    function gpiod_edge_event_get_line_offset(GPIOEdgeEvent $event): int
    {
        return EdgeEvent::gpioEdgeEventGetLineOffset($event);
    }
}

if(!function_exists('gpiod_edge_event_get_global_seqno'))
{
    function gpiod_edge_event_get_global_seqno(GPIOEdgeEvent $event): int
    {
        return EdgeEvent::gpioEdgeEventGetGlobalSeqno($event);
    }
}

if(!function_exists('gpiod_edge_event_get_line_seqno'))
{
    function gpiod_edge_event_get_line_seqno(GPIOEdgeEvent $event): int
    {
        return EdgeEvent::gpioEdgeEventGetLineSeqno($event);
    }
}
