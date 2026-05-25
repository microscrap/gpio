<?php

namespace Microscrap\Bindings\GPIO;

use Microscrap\Bindings\GPIO\Concerns\LocalChipMethods;
use Microscrap\Bindings\GPIO\DataObjects\GPIOChip;
use Microscrap\Bindings\GPIO\DataObjects\GPIOChipInfo;
use Microscrap\Bindings\GPIO\DataObjects\GPIOInfoEvent;
use Microscrap\Bindings\GPIO\DataObjects\GPIOLineConfig;
use Microscrap\Bindings\GPIO\DataObjects\GPIOLineInfo;
use Microscrap\Bindings\GPIO\DataObjects\GPIOLineRequest;
use Microscrap\Bindings\GPIO\DataObjects\GPIORequestConfig;
use Microscrap\Bindings\GPIO\DataObjects\GPIOV2LineInfoChanged;
use Microscrap\Bindings\GPIO\DataObjects\GPIOV2LineConfig;
use Microscrap\Bindings\GPIO\DataObjects\GPIOV2LineRequest;
use Microscrap\Bindings\GPIO\Enums\GPIOOpCode;
use Microscrap\Bindings\POSIX\Enums\FileControlFlag;
use Posi\System;

class Chip
{
    use LocalChipMethods;

    public static function gpioChipOpen(string $path): ?GPIOChip
    {
        if (! gpiod_check_gpiochip_device($path)) {
            return null;
        }

        $flags = FileControlFlag::O_RDWR->value | FileControlFlag::O_CLOEXEC->value;
        $fd = posix_open($path, $flags);
        if ($fd < 0) {
            return null;
        }

        return new GPIOChip($fd, $path);
    }

    public static function gpioChipClose(GPIOChip $chip): int
    {
        return posix_close($chip->fd);
    }

    public static function gpioChipGetInfo(GPIOChip $chip): ?GPIOChipInfo
    {
        $info = null;
        $ret = static::readChipInfo($chip->fd, $info);

        if ($ret < 0) {
            return null;
        }

        /** @var GPIOChipInfo $info */
        return gpiod_chip_info_from_uapi($info);
    }

    public static function gpioChipGetPath(GPIOChip $chip): string
    {
        return $chip->path;
    }

    public static function gpioChipGetLineInfo(GPIOChip $chip, int $offset): ?GPIOLineInfo
    {
        return static::chipGetLineInfo($chip, $offset, false);
    }

    public static function gpioChipWatchLineInfo(GPIOChip $chip, int $offset): ?GPIOLineInfo
    {
        return static::chipGetLineInfo($chip, $offset, true);
    }

    public static function gpioChipUnwatchLineInfo(GPIOChip $chip, int $offset): int
    {
        $buffer = pack('V', $offset);

        return gpiod_ioctl(
            $chip->fd,
            GPIOOpCode::GPIO_GET_LINE_INFO_UNWATCH,
            ['data' => $buffer],
            $buffer,
        );
    }

    public static function gpioChipGetFD(GPIOChip $chip): int
    {
        return $chip->fd;
    }

    public static function gpioChipWaitInfoEvent(GPIOChip $chip, int $timeout_ns): ?int
    {
        return System::ppoll($chip->fd, $timeout_ns);
    }

    public static function gpioChipReadInfoEvent(GPIOChip $chip): ?GPIOInfoEvent
    {
        $buffer = System::read($chip->fd, 288);
        if (! is_string($buffer)) {
            return null;
        }

        $info_changed = GPIOV2LineInfoChanged::fromBuffer($buffer);
        if (! $info_changed instanceof GPIOV2LineInfoChanged) {
            return null;
        }

        $uapi_line_info = $info_changed->info;
        $line_info = gpiod_line_info_from_uapi($uapi_line_info);
        if (! $line_info instanceof GPIOLineInfo) {
            return null;
        }

        return new GPIOInfoEvent(
            event_type: $info_changed->event_type,
            timestamp: $info_changed->timestamp_ns,
            info: $line_info,
        );
    }

    public static function gpioChipGetLineOffsetFromName(GPIOChip $chip, string $name): ?int
    {
        $info = gpiod_chip_get_info($chip);
        if (! $info instanceof GPIOChipInfo) {
            return null;
        }

        for ($offset = 0; $offset < $info->num_lines; $offset++) {
            $line_info = gpiod_chip_get_line_info($chip, $offset);
            if (! $line_info instanceof GPIOLineInfo) {
                continue;
            }

            if ($line_info->name === $name) {
                return $offset;
            }
        }

        return null;
    }

    public static function gpioChipRequestLines(GPIOChip $chip, GPIORequestConfig $request, GPIOLineConfig $line_config): ?GPIOLineRequest
    {
        $num_lines = gpiod_line_config_get_num_configured_offsets($line_config);
        if ($num_lines <= 0 || $num_lines > 64) { // GPIO_V2_LINES_MAX
            return null;
        }

        $offsets = gpiod_line_config_get_configured_offsets($line_config, 64); // GPIO_V2_LINES_MAX
        if (count($offsets) !== $num_lines) {
            return null;
        }

        foreach ($offsets as $offset) {
            if (! is_int($offset) || $offset < 0) {
                return null;
            }
        }

        $uapi_config = gpiod_line_config_to_uapi($line_config);
        if (! $uapi_config instanceof GPIOV2LineConfig) {
            return null;
        }

        $uapi_request = new GPIOV2LineRequest(
            offsets: $offsets,
            consumer: gpiod_request_config_get_consumer($request),
            config: $uapi_config,
            num_lines: $num_lines,
            event_buffer_size: gpiod_request_config_get_event_buffer_size($request),
        );

        $buffer = $uapi_request->toBuffer();
        $ret = gpiod_ioctl(
            $chip->fd,
            GPIOOpCode::GPIO_V2_GET_LINE,
            ['data' => $buffer],
            $buffer,
        );
        if ($ret !== 0 || ! is_string($buffer)) {
            return null;
        }

        $result = GPIOV2LineRequest::fromBuffer($buffer);
        if (! $result instanceof GPIOV2LineRequest || $result->fd < 0) {
            return null;
        }

        $chip_name = basename($chip->path);
        $chip_info = gpiod_chip_get_info($chip);
        if ($chip_info instanceof GPIOChipInfo && $chip_info->name !== '') {
            $chip_name = $chip_info->name;
        }

        return new GPIOLineRequest(
            chip_name: $chip_name,
            offsets: $offsets,
            num_lines: $num_lines,
            fd: $result->fd,
        );
    }
}
