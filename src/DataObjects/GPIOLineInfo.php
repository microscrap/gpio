<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

use Microscrap\Bindings\GPIO\Enums\LineBias;
use Microscrap\Bindings\GPIO\Enums\LineClock;
use Microscrap\Bindings\GPIO\Enums\LineDirection;
use Microscrap\Bindings\GPIO\Enums\LineDrive;
use Microscrap\Bindings\GPIO\Enums\LineEdge;

/**
 * struct gpiod_line_info
 */
final readonly class GPIOLineInfo
{
    public function __construct(
        public int $offset,
        public string $name,
        public bool $used,
        public string $consumer,
        public LineDirection $direction,
        public bool $active_low,
        public LineBias $bias,
        public LineDrive $drive,
        public LineEdge $edge,
        public LineClock $event_clock,
        public bool $debounced,
        public int $debounce_period_us,
    ) {}
}
