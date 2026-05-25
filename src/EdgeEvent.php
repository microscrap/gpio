<?php

namespace Microscrap\Bindings\GPIO;

use Microscrap\Bindings\GPIO\DataObjects\GPIOEdgeEvent;
use Microscrap\Bindings\GPIO\Enums\EdgeEventType;

class EdgeEvent
{
    public static function gpioEdgeEventCopy(GPIOEdgeEvent $event): GPIOEdgeEvent
    {
        return clone $event;
    }

    public static function gpioEdgeEventGetEventType(GPIOEdgeEvent $event): EdgeEventType
    {
        return $event->event_type;
    }

    public static function gpioEdgeEventGetTimestampNs(GPIOEdgeEvent $event): int
    {
        return $event->timestamp_ns;
    }

    public static function gpioEdgeEventGetLineOffset(GPIOEdgeEvent $event): int
    {
        return $event->line_offset;
    }

    public static function gpioEdgeEventGetGlobalSeqno(GPIOEdgeEvent $event): int
    {
        return $event->global_seqno;
    }

    public static function gpioEdgeEventGetLineSeqno(GPIOEdgeEvent $event): int
    {
        return $event->line_seqno;
    }
}
