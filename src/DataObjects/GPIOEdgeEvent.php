<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

use Microscrap\Bindings\GPIO\Enums\EdgeEventType;

/**
 * struct gpiod_edge_event
 */
final readonly class GPIOEdgeEvent
{
    public function __construct(
        public EdgeEventType $event_type,
        public int $timestamp_ns,
        public int $line_offset,
        public int $global_seqno,
        public int $line_seqno,
    ) {}
}
