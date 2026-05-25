<?php

namespace Microscrap\Bindings\GPIO\Enums;

/**
 * Identifies the active member of the union inside struct gpio_v2_line_attribute.
 * Maps to GPIO_V2_LINE_ATTR_ID_* in linux/gpio.h.
 */
enum GPIOV2LineAttrId: int
{
    case FLAGS         = 1;  // union field: __u64 flags (line-flag override bitmask)
    case OUTPUT_VALUES = 2;  // union field: __u64 values (bitmap of output values)
    case DEBOUNCE      = 3;  // union field: __u32 debounce_period_us
}
