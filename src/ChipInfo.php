<?php

namespace Microscrap\Bindings\GPIO;

use Microscrap\Bindings\GPIO\DataObjects\GPIOChipInfo;

class ChipInfo
{
    /**
     * Port of gpiod_chip_info_from_uapi() from lib/chip-info.c.
     *
     * Converts a raw kernel gpiochip_info payload (already unpacked into a
     * GPIOChipInfo by readChipInfo) into the library-level GPIOChipInfo object.
     *
     * Mirrors the C behaviour exactly:
     *   - name  is copied as-is (kernel always provides one)
     *   - label defaults to "unknown" when the kernel left it empty
     *   - both strings are capped at GPIO_MAX_NAME_SIZE (32) characters
     */
    public static function GpioChipInfoFromUapi(GPIOChipInfo $uapi_info): ?GPIOChipInfo
    {
        $name = substr($uapi_info->name, 0, 32);

        $label = $uapi_info->label === ''
            ? 'unknown'
            : substr($uapi_info->label, 0, 32);

        return new GPIOChipInfo(
            num_lines: $uapi_info->num_lines,
            name:      $name,
            label:     $label,
        );
    }

    public static function gpioChipInfoGetName(GPIOChipInfo $info): string
    {
        return $info->name;
    }

    public static function gpioChipInfoGetLabel(GPIOChipInfo $info): string
    {
        return $info->label;
    }

    public static function gpioChipInfoGetNumLines(GPIOChipInfo $info): int
    {
        return $info->num_lines;
    }
}
