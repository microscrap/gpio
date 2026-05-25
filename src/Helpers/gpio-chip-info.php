<?php

use Microscrap\Bindings\GPIO\ChipInfo;
use Microscrap\Bindings\GPIO\DataObjects\GPIOChipInfo;

if(!function_exists('gpiod_chip_info_from_uapi'))
{
    function gpiod_chip_info_from_uapi(GPIOChipInfo &$uapi_info): ?GPIOChipInfo
    {
        return ChipInfo::GpioChipInfoFromUapi($uapi_info);
    }
}

if(!function_exists('gpiod_chip_info_get_name'))
{
    function gpiod_chip_info_get_name(GPIOChipInfo $info): string
    {
        return ChipInfo::gpioChipInfoGetName($info);
    }
}

if(!function_exists('gpiod_chip_info_get_label'))
{
    function gpiod_chip_info_get_label(GPIOChipInfo $info): string
    {
        return ChipInfo::gpioChipInfoGetLabel($info);
    }
}

if(!function_exists('gpiod_chip_info_get_num_lines'))
{
    function gpiod_chip_info_get_num_lines(GPIOChipInfo $info): int
    {
        return ChipInfo::gpioChipInfoGetNumLines($info);
    }
}