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
    public LineDirection $direction = LineDirection::AsIs;
    public LineEdge $edge_detection = LineEdge::None;
    public LineDrive $drive = LineDrive::PushPull;
    public LineBias $bias = LineBias::AsIs;
    public bool $active_low = false;
    public LineClock $event_clock = LineClock::Monotonic;
    public int $debounce_period_us = 0;
    public LineValue $output_value = LineValue::Inactive;
}
