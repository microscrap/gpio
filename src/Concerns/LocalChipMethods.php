<?php

namespace Microscrap\Bindings\GPIO\Concerns;

use Microscrap\Bindings\GPIO\DataObjects\GPIOChip;
use Microscrap\Bindings\GPIO\DataObjects\GPIOChipInfo;
use Microscrap\Bindings\GPIO\DataObjects\GPIOLineInfo;
use Microscrap\Bindings\GPIO\DataObjects\GPIOV2LineInfo;
use Microscrap\Bindings\GPIO\Enums\GPIOOpCode;

trait LocalChipMethods
{
    protected static function readChipInfo(int $fd, ?GPIOChipInfo &$info, int $info_size = 68, $max_name_size = 32): int
    {
        $buffer = str_repeat("\0", $info_size);

        $ret = gpiod_ioctl(
            $fd,
            GPIOOpCode::GPIO_GET_CHIP_INFO,
            ['data' => $buffer],
            $buffer,
        );

        if ($ret !== 0) {
            return -1;
        }

        if (! is_string($buffer)) {
            return -1;
        }

        $info = new GPIOChipInfo(
            num_lines: unpack('V', substr($buffer, 64, 4))[1],
            name: rtrim(substr($buffer, 0, $max_name_size), "\0"),
            label: rtrim(substr($buffer, 32, $max_name_size), "\0"),
        );

        return 0;
    }

    protected static function chipReadLineInfo(int $fd, int $offset, ?GPIOV2LineInfo &$info, bool $watch): int
    {
        // 256-byte zero buffer matching struct gpio_v2_line_info.
        // The kernel reads the offset field at byte 64, so we seed it before the ioctl.
        $buffer = str_repeat("\0", 64) . pack('V', $offset) . str_repeat("\0", 188);

        $cmd = $watch ? GPIOOpCode::GPIO_V2_GET_LINEINFO_WATCH : GPIOOpCode::GPIO_V2_GET_LINEINFO;
        $ret = gpiod_ioctl($fd, $cmd, ['data' => $buffer], $buffer);

        if ($ret !== 0) {
            return -1;
        }

        if (! is_string($buffer)) {
            return -1;
        }

        $info = GPIOV2LineInfo::fromBuffer($buffer);

        return 0;
    }

    protected static function chipGetLineInfo(GPIOChip $chip, int $offset, bool $watch): ?GPIOLineInfo
    {
        $info = null;
        $ret = static::chipReadLineInfo($chip->fd, $offset, $info, $watch);
        if($ret)
        {
            return null;
        }

        /** @var GPIOV2LineInfo $info */
        return gpiod_line_info_from_uapi($info);
    }
}