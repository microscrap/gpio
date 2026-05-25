<?php

use Microscrap\Bindings\GPIO\Chip;
use Microscrap\Bindings\GPIO\DataObjects\GPIOChip;
use Microscrap\Bindings\GPIO\DataObjects\GPIOChipInfo;
use Microscrap\Bindings\GPIO\DataObjects\GPIOInfoEvent;
use Microscrap\Bindings\GPIO\DataObjects\GPIOLineConfig;
use Microscrap\Bindings\GPIO\DataObjects\GPIOLineInfo;
use Microscrap\Bindings\GPIO\DataObjects\GPIOLineRequest;
use Microscrap\Bindings\GPIO\DataObjects\GPIORequestConfig;

if(! function_exists('gpiod_chip_open')){
    function gpiod_chip_open(string $path): ?GPIOChip
    {
        return Chip::gpioChipOpen($path);
    }
}

if(! function_exists('gpiod_chip_close')){
    function gpiod_chip_close(GPIOChip $chip): int
    {
        return Chip::gpioChipClose($chip);
    }
}

if(! function_exists('gpiod_chip_get_info')){
    function gpiod_chip_get_info(GPIOChip $chip): ?GPIOChipInfo
    {
        return Chip::gpioChipGetInfo($chip);
    }
}

if(! function_exists('gpiod_chip_get_path')){
    function gpiod_chip_get_path(GPIOChip $chip): string
    {
        return Chip::gpioChipGetPath($chip);
    }
}

if(! function_exists('gpiod_chip_get_line_info')){
    function gpiod_chip_get_line_info(GPIOChip $chip, int $offset): ?GPIOLineInfo
    {
        return Chip::gpioChipGetLineInfo($chip, $offset);
    }
}

if(! function_exists('gpiod_chip_watch_line_info')){
    function gpiod_chip_watch_line_info(GPIOChip $chip, int $offset): ?GPIOLineInfo
    {
        return Chip::gpioChipWatchLineInfo($chip, $offset);
    }
}

if(! function_exists('gpiod_chip_unwatch_line_info')){
    function gpiod_chip_unwatch_line_info(GPIOChip $chip, int $offset): int
    {
        return Chip::gpioChipUnwatchLineInfo($chip, $offset);
    }
}

if(! function_exists('gpiod_chip_get_fd')){
    function gpiod_chip_get_fd(GPIOChip $chip): int
    {
        return Chip::gpioChipGetFD($chip);
    }
}

if(! function_exists('gpiod_chip_wait_info_event')){
    function gpiod_chip_wait_info_event(GPIOChip $chip, int $timeout_ns): ?int
    {
        return Chip::gpioChipWaitInfoEvent($chip, $timeout_ns);
    }
}

if(! function_exists('gpiod_chip_read_info_event')){
    function gpiod_chip_read_info_event(GPIOChip $chip): ?GPIOInfoEvent
    {
        return Chip::gpioChipReadInfoEvent($chip);
    }
}

if(! function_exists('gpiod_chip_get_line_offset_from_name')){
    function gpiod_chip_get_line_offset_from_name(GPIOChip $chip, string $name): ?int
    {
        return Chip::gpioChipGetLineOffsetFromName($chip, $name);
    }
}

if(! function_exists('gpiod_chip_request_lines')){
    function gpiod_chip_request_lines(GPIOChip $chip, GPIORequestConfig $request, GPIOLineConfig $line_config): ?GPIOLineRequest
    {
        return Chip::gpioChipRequestLines($chip, $request, $line_config);
    }
}