<?php

namespace Microscrap\Bindings\GPIO\Enums;

/**
 * GPIO ioctl opcodes derived from linux/gpio.h _IOR/_IOWR macros.
 *
 * Formula:
 *   _IOR (read-only) : (2 << 30) | (sizeof(T) << 16) | (type << 8) | nr
 *   _IOWR (read/write): (3 << 30) | (sizeof(T) << 16) | (type << 8) | nr
 *
 * All use magic type = 0xB4.
 */
enum GPIOOpCode: int
{
    // _IOR(0xB4, 0x01, struct gpiochip_info)         sizeof = 68  = 0x44
    case GPIO_GET_CHIP_INFO            = 0x8044B401;

    // _IOWR(0xB4, 0x05, struct gpio_v2_line_info)    sizeof = 256 = 0x100
    case GPIO_V2_GET_LINEINFO          = 0xC100B405;

    // _IOWR(0xB4, 0x06, struct gpio_v2_line_info)    sizeof = 256 = 0x100
    case GPIO_V2_GET_LINEINFO_WATCH     = 0xC100B406;

    // _IOWR(0xB4, 0x07, struct gpio_v2_line_request) sizeof = 592 = 0x250
    case GPIO_V2_GET_LINE              = 0xC250B407;

    // _IOWR(0xB4, 0x0C, __u32)                       sizeof =   4 = 0x04
    case GPIO_GET_LINE_INFO_UNWATCH     = 0xC004B40C;

    // _IOWR(0xB4, 0x0D, struct gpio_v2_line_config)  sizeof = 272 = 0x110
    case GPIO_V2_LINE_SET_CONFIG        = 0xC110B40D;

    // _IOWR(0xB4, 0x0E, struct gpio_v2_line_values)  sizeof =  16 = 0x10
    case GPIO_V2_LINE_GET_VALUES        = 0xC010B40E;

    // _IOWR(0xB4, 0x0F, struct gpio_v2_line_values)  sizeof =  16 = 0x10
    case GPIO_V2_LINE_SET_VALUES        = 0xC010B40F;
}
