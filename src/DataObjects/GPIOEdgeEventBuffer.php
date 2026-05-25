<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

/**
 * struct gpiod_edge_event_buffer
 */
final class GPIOEdgeEventBuffer
{
    /**
     * @param array<int, GPIOEdgeEvent> $events
     */
    public function __construct(
        public int $capacity,
        public int $num_events = 0,
        public array $events = [],
    ) {}
}
