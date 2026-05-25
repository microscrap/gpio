<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

use Microscrap\Bindings\GPIO\Enums\InfoEventType;

/**
 * struct gpiod_info_event
 */
final readonly class GPIOInfoEvent
{
    public function __construct(
        public InfoEventType $event_type,
        public int $timestamp,
        public GPIOLineInfo $info,
    ) {}
}
