<?php

namespace Microscrap\Bindings\GPIO;

class Misc
{
    public static function gpioMiscIsGPIOChipDevice(string $path): bool
    {
        return Internal::gpioCheckGPIOChipDevice($path);
    }

    public static function gpioMiscAPIVersion(): string
    {
        return '0.4.0';
    }
}
