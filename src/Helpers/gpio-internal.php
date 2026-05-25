<?php

use Microscrap\Bindings\GPIO\Internal;
use Microscrap\Bindings\GPIO\Enums\GPIOOpCode;

if(! function_exists('gpiod_check_gpiochip_device')){
    function gpiod_check_gpiochip_device(string $path): bool
    {
        return Internal::gpioCheckGPIOChipDevice($path);
    }
}

if(! function_exists('gpiod_ioctl')){
    function gpiod_ioctl(int $fd, GPIOOpCode $request, mixed $arg, mixed &$value): int
    {
        return Internal::gpioIoctl($fd, $request, $arg, $value);
    }
}