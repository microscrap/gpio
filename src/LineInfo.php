<?php

namespace Microscrap\Bindings\GPIO;

use Microscrap\Bindings\GPIO\DataObjects\GPIOLineInfo;
use Microscrap\Bindings\GPIO\DataObjects\GPIOV2LineInfo;
use Microscrap\Bindings\GPIO\Enums\GPIOV2LineAttrId;
use Microscrap\Bindings\GPIO\Enums\GPIOV2LineFlag;
use Microscrap\Bindings\GPIO\Enums\LineBias;
use Microscrap\Bindings\GPIO\Enums\LineClock;
use Microscrap\Bindings\GPIO\Enums\LineDirection;
use Microscrap\Bindings\GPIO\Enums\LineDrive;
use Microscrap\Bindings\GPIO\Enums\LineEdge;

class LineInfo
{
    /**
     * Port of gpiod_line_info_from_uapi() from lib/chip.c.
     *
     * Converts a raw kernel gpio_v2_line_info payload (already unpacked into a
     * GPIOV2LineInfo by chipReadLineInfo) into the library-level GPIOLineInfo.
     */
    public static function GpioLineInfoFromUapi(GPIOV2LineInfo &$uapi_info, int $max_name_size = 32): ?GPIOLineInfo
    {
        // --- Direction ---
        $direction = $uapi_info->hasFlag(GPIOV2LineFlag::OUTPUT)
            ? LineDirection::Output
            : LineDirection::Input;

        // --- Drive ---
        if ($uapi_info->hasFlag(GPIOV2LineFlag::OPEN_DRAIN)) {
            $drive = LineDrive::OpenDrain;
        } elseif ($uapi_info->hasFlag(GPIOV2LineFlag::OPEN_SOURCE)) {
            $drive = LineDrive::OpenSource;
        } else {
            $drive = LineDrive::PushPull;
        }

        // --- Bias ---
        if ($uapi_info->hasFlag(GPIOV2LineFlag::BIAS_PULL_UP)) {
            $bias = LineBias::PullUp;
        } elseif ($uapi_info->hasFlag(GPIOV2LineFlag::BIAS_PULL_DOWN)) {
            $bias = LineBias::PullDown;
        } elseif ($uapi_info->hasFlag(GPIOV2LineFlag::BIAS_DISABLED)) {
            $bias = LineBias::Disabled;
        } else {
            $bias = LineBias::Unknown;
        }

        // --- Edge detection ---
        $rising  = $uapi_info->hasFlag(GPIOV2LineFlag::EDGE_RISING);
        $falling = $uapi_info->hasFlag(GPIOV2LineFlag::EDGE_FALLING);

        $edge = match (true) {
            $rising && $falling => LineEdge::Both,
            $rising             => LineEdge::Rising,
            $falling            => LineEdge::Falling,
            default             => LineEdge::None,
        };

        // --- Event clock ---
        if ($uapi_info->hasFlag(GPIOV2LineFlag::EVENT_CLOCK_REALTIME)) {
            $event_clock = LineClock::Realtime;
        } elseif ($uapi_info->hasFlag(GPIOV2LineFlag::EVENT_CLOCK_HTE)) {
            $event_clock = LineClock::Hte;
        } else {
            $event_clock = LineClock::Monotonic;
        }

        // --- Debounce: scan attrs for GPIOV2LineAttrId::DEBOUNCE ---
        $debounced          = false;
        $debounce_period_us = 0;

        foreach ($uapi_info->attrs as $attr) {
            if ($attr->id === GPIOV2LineAttrId::DEBOUNCE) {
                $debounced          = true;
                $debounce_period_us = (int) $attr->debounce_period_us;
                break;
            }
        }

        return new GPIOLineInfo(
            offset:             $uapi_info->offset,
            name:               substr($uapi_info->name, 0, $max_name_size),
            used:               $uapi_info->hasFlag(GPIOV2LineFlag::USED),
            consumer:           substr($uapi_info->consumer, 0, $max_name_size),
            direction:          $direction,
            active_low:         $uapi_info->hasFlag(GPIOV2LineFlag::ACTIVE_LOW),
            bias:               $bias,
            drive:              $drive,
            edge:               $edge,
            event_clock:        $event_clock,
            debounced:          $debounced,
            debounce_period_us: $debounce_period_us,
        );
    }

    public static function gpioLineInfoCopy(GPIOLineInfo $info): GPIOLineInfo
    {
        return clone $info;
    }

    public static function gpioLineInfoGetOffset(GPIOLineInfo $info): int
    {
        return $info->offset;
    }

    public static function gpioLineInfoGetName(GPIOLineInfo $info): string
    {
        return $info->name;
    }

    public static function gpioLineInfoIsUsed(GPIOLineInfo $info): bool
    {
        return $info->used;
    }

    public static function gpioLineInfoGetConsumer(GPIOLineInfo $info): string
    {
        return $info->consumer;
    }

    public static function gpioLineInfoGetDirection(GPIOLineInfo $info): LineDirection
    {
        return $info->direction;
    }

    public static function gpioLineInfoGetEdgeDetection(GPIOLineInfo $info): LineEdge
    {
        return $info->edge;
    }

    public static function gpioLineInfoGetBias(GPIOLineInfo $info): LineBias
    {
        return $info->bias;
    }

    public static function gpioLineInfoGetDrive(GPIOLineInfo $info): LineDrive
    {
        return $info->drive;
    }

    public static function gpioLineInfoIsActiveLow(GPIOLineInfo $info): bool
    {
        return $info->active_low;
    }

    public static function gpioLineInfoIsDebounced(GPIOLineInfo $info): bool
    {
        return $info->debounced;
    }

    public static function gpioLineInfoGetDebouncePeriodUs(GPIOLineInfo $info): int
    {
        return $info->debounce_period_us;
    }

    public static function gpioLineInfoGetEventClock(GPIOLineInfo $info): LineClock
    {
        return $info->event_clock;
    }
}
