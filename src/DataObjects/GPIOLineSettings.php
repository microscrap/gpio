<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

use Microscrap\Bindings\GPIO\Enums\LineBias;
use Microscrap\Bindings\GPIO\Enums\LineClock;
use Microscrap\Bindings\GPIO\Enums\LineDirection;
use Microscrap\Bindings\GPIO\Enums\LineDrive;
use Microscrap\Bindings\GPIO\Enums\LineEdge;
use Microscrap\Bindings\GPIO\Enums\LineValue;

/**
 * struct gpiod_line_settings
 */
class GPIOLineSettings
{
    public LineDirection $direction = LineDirection::AS_IS;
    public LineEdge $edge_detection = LineEdge::NONE;
    public LineDrive $drive = LineDrive::PUSH_PULL;
    public LineBias $bias = LineBias::AS_IS;
    public bool $active_low = false;
    public LineClock $event_clock = LineClock::MONOTONIC;
    public int $debounce_period_us = 0;
    public LineValue $output_value = LineValue::INACTIVE;
}
