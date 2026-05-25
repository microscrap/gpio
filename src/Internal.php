<?php

namespace Microscrap\Bindings\GPIO;

use Microscrap\Bindings\GPIO\Enums\StatMode;
use Microscrap\Bindings\GPIO\Enums\GPIOOpCode;

class Internal
{
    public static function gpioCheckGPIOChipDevice(string $path): bool
    {
        $statbuf = posix_lstat($path);
        if ($statbuf === false) {
            return false;
        }

        // Is it a symbolic link? Resolve it before checking the rest.
        $realname = (($statbuf['mode'] & StatMode::S_IFMT->value) === StatMode::S_IFLNK->value)
            ? realpath($path)
            : $path;

        if ($realname === false) {
            return false;
        }

        $statbuf = posix_lstat($realname);
        if ($statbuf === false) {
            return false;
        }

        // Is it a character device?
        if (($statbuf['mode'] & StatMode::S_IFMT->value) !== StatMode::S_IFCHR->value) {
            return false;
        }

        // Is the device associated with the GPIO subsystem?
        $rdev = $statbuf['rdev'];
        $maj  = (($rdev >> 8) & 0xfff) | (($rdev >> 32) << 12);
        $min  = ($rdev & 0xff)          | (($rdev >> 12) & ~0xff);

        $devpath = sprintf('/sys/dev/char/%u:%u/subsystem', $maj, $min);

        $sysfsp = realpath($devpath);
        if ($sysfsp === false) {
            return false;
        }

        return $sysfsp === '/sys/bus/gpio';
    }

    public static function gpioIoctl(int $fd, GPIOOpCode $request, mixed $arg, mixed &$value): int
    {
        $ret = ioctl($fd, $request->value, $arg, $value);

        if ($ret <= 0) {
            return $ret;
        }

        return -1;
    }
}