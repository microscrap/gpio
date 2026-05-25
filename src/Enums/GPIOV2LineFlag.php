<?php

namespace Microscrap\Bindings\GPIO\Enums;

/**
 * Bitmask flags for gpio_v2_line_info.flags / gpio_v2_line_attribute (id=1).
 * Maps to the gpio_v2_line_flag enum in linux/gpio.h.
 */
enum GPIOV2LineFlag: int
{
    case USED                 = 1 << 0;   // 0x0001  line is in use
    case ACTIVE_LOW           = 1 << 1;   // 0x0002  line is active-low
    case INPUT                = 1 << 2;   // 0x0004  direction: input
    case OUTPUT               = 1 << 3;   // 0x0008  direction: output
    case EDGE_RISING          = 1 << 4;   // 0x0010  rising-edge events enabled
    case EDGE_FALLING         = 1 << 5;   // 0x0020  falling-edge events enabled
    case OPEN_DRAIN           = 1 << 6;   // 0x0040  drive: open-drain
    case OPEN_SOURCE          = 1 << 7;   // 0x0080  drive: open-source
    case BIAS_PULL_UP         = 1 << 8;   // 0x0100  pull-up bias
    case BIAS_PULL_DOWN       = 1 << 9;   // 0x0200  pull-down bias
    case BIAS_DISABLED        = 1 << 10;  // 0x0400  bias disabled
    case EVENT_CLOCK_REALTIME = 1 << 11;  // 0x0800  event timestamps from CLOCK_REALTIME
    case EVENT_CLOCK_HTE      = 1 << 12;  // 0x1000  event timestamps from hardware timestamping engine
}
