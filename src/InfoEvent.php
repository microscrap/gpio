<?php

namespace Microscrap\Bindings\GPIO;

use Microscrap\Bindings\GPIO\DataObjects\GPIOInfoEvent;
use Microscrap\Bindings\GPIO\DataObjects\GPIOLineInfo;
use Microscrap\Bindings\GPIO\Enums\InfoEventType;

class InfoEvent
{
    public static function gpioInfoEventGetEventType(GPIOInfoEvent $event): InfoEventType
    {
        return $event->event_type;
    }

    public static function gpioInfoEventGetTimestampNs(GPIOInfoEvent $event): int
    {
        return $event->timestamp;
    }

    public static function gpioInfoEventGetLineInfo(GPIOInfoEvent $event): GPIOLineInfo
    {
        return $event->info;
    }
}
