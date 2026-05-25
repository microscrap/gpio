<?php

use Microscrap\Bindings\GPIO\Misc;

if(!function_exists('gpiod_is_gpiochip_device'))
{
    function gpiod_is_gpiochip_device(string $path): bool
    {
        return Misc::gpioMiscIsGPIOChipDevice($path);
    }
}

if(!function_exists('gpiod_api_version'))
{
    function gpiod_api_version(): string
    {
        return Misc::gpioMiscAPIVersion();
    }
}
