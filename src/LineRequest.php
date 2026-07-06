<?php

namespace Microscrap\Bindings\GPIO;

use Microscrap\Bindings\GPIO\DataObjects\GPIOLineConfig;
use Microscrap\Bindings\GPIO\DataObjects\GPIOEdgeEvent;
use Microscrap\Bindings\GPIO\DataObjects\GPIOEdgeEventBuffer;
use Microscrap\Bindings\GPIO\DataObjects\GPIOLineRequest;
use Microscrap\Bindings\GPIO\DataObjects\GPIOV2LineEvent;
use Microscrap\Bindings\GPIO\DataObjects\GPIOV2LineConfig;
use Microscrap\Bindings\GPIO\DataObjects\GPIOV2LineValues;
use Microscrap\Bindings\GPIO\Enums\GPIOOpCode;
use Microscrap\Bindings\GPIO\Enums\LineValue;
use Posi\System;

class LineRequest
{
    public static function gpioLineRequestRelease(GPIOLineRequest $request): int
    {
        return System::close($request->fd);
    }

    public static function gpioLineRequestGetFD(GPIOLineRequest $request): int
    {
        return $request->fd;
    }

    public static function gpioLineRequestGetChipName(GPIOLineRequest $request): string
    {
        return $request->chip_name;
    }

    public static function gpioLineRequestGetNumRequestedLines(GPIOLineRequest $request): int
    {
        return $request->num_lines;
    }

    /**
     * @return array<int, int>
     */
    public static function gpioLineRequestGetRequestedOffsets(GPIOLineRequest $request, int $max_offsets): array
    {
        if ($max_offsets <= 0) {
            return [];
        }

        $available = min($request->num_lines, count($request->offsets));
        $count = min($available, $max_offsets);

        return array_slice($request->offsets, 0, $count);
    }

    /**
     * @return array<int, LineValue>|null
     */
    public static function gpioLineRequestGetValues(GPIOLineRequest $request): ?array
    {
        $offsets = static::gpioLineRequestGetRequestedOffsets($request, $request->num_lines);

        return static::gpioLineRequestGetValuesSubset($request, $offsets);
    }

    public static function gpioLineRequestGetValue(GPIOLineRequest $request, int $offset): ?LineValue
    {
        $values = static::gpioLineRequestGetValuesSubset($request, [$offset]);
        if (! is_array($values) || count($values) !== 1) {
            return null;
        }

        return $values[0];
    }

    /**
     * @param array<int, int> $offsets
     * @return array<int, LineValue>|null
     */
    public static function gpioLineRequestGetValuesSubset(GPIOLineRequest $request, array $offsets): ?array
    {
        if (count($offsets) === 0) {
            return [];
        }

        $uapi = new GPIOV2LineValues();
        $indices = [];

        foreach ($offsets as $offset) {
            if (! is_int($offset) || $offset < 0) {
                return null;
            }

            $line_index = static::indexForOffset($request, $offset);
            if ($line_index === null) {
                return null;
            }

            $uapi->mask = static::assignMaskBit($uapi->mask, $line_index, true);
            $indices[] = $line_index;
        }

        $buffer = $uapi->toBuffer();
        $ret = gpiod_ioctl(
            $request->fd,
            GPIOOpCode::GPIO_V2_LINE_GET_VALUES,
            ['data' => $buffer],
            $buffer,
        );
        if ($ret !== 0 || ! is_string($buffer)) {
            return null;
        }

        $result = GPIOV2LineValues::fromBuffer($buffer);
        if (! $result instanceof GPIOV2LineValues) {
            return null;
        }

        $values = [];
        foreach ($indices as $index) {
            $values[] = static::isMaskBitSet($result->bits, $index)
                ? LineValue::ACTIVE
                : LineValue::INACTIVE;
        }

        return $values;
    }

    /**
     * @param array<int, LineValue|int> $values
     */
    public static function gpioLineRequestSetValues(GPIOLineRequest $request, array $values): int
    {
        $offsets = static::gpioLineRequestGetRequestedOffsets($request, $request->num_lines);
        if (count($offsets) !== count($values)) {
            return -1;
        }

        return static::gpioLineRequestSetValuesSubset($request, $offsets, $values);
    }

    public static function gpioLineRequestSetValue(GPIOLineRequest $request, int $offset, LineValue|int $value): int
    {
        return static::gpioLineRequestSetValuesSubset($request, [$offset], [$value]);
    }

    /**
     * @param array<int, int> $offsets
     * @param array<int, LineValue|int> $values
     */
    public static function gpioLineRequestSetValuesSubset(GPIOLineRequest $request, array $offsets, array $values): int
    {
        $count = count($offsets);
        if ($count === 0 || $count !== count($values)) {
            return -1;
        }

        $uapi = new GPIOV2LineValues();

        foreach ($offsets as $index => $offset) {
            if (! is_int($offset) || $offset < 0) {
                return -1;
            }

            $resolved = $values[$index] instanceof LineValue
                ? $values[$index]
                : LineValue::tryFrom($values[$index]);
            if (! $resolved instanceof LineValue || $resolved === LineValue::ERROR) {
                return -1;
            }

            $line_index = static::indexForOffset($request, $offset);
            if ($line_index === null) {
                return -1;
            }

            $uapi->mask = static::assignMaskBit($uapi->mask, $line_index, true);
            $uapi->bits = static::assignMaskBit($uapi->bits, $line_index, $resolved === LineValue::ACTIVE);
        }

        $buffer = $uapi->toBuffer();

        return gpiod_ioctl(
            $request->fd,
            GPIOOpCode::GPIO_V2_LINE_SET_VALUES,
            ['data' => $buffer],
            $buffer,
        );
    }

    public static function gpioLineRequestReconfigureLines(GPIOLineRequest $request, GPIOLineConfig $config): int
    {
        $uapi_config = gpiod_line_config_to_uapi($config);
        if (! $uapi_config instanceof GPIOV2LineConfig) {
            return -1;
        }

        $buffer = $uapi_config->toBuffer();

        return gpiod_ioctl(
            $request->fd,
            GPIOOpCode::GPIO_V2_LINE_SET_CONFIG,
            ['data' => $buffer],
            $buffer,
        );
    }

    public static function gpioLineRequestWaitEdgeEvents(GPIOLineRequest $request, int $timeout_ns): ?int
    {
        return System::ppoll($request->fd, $timeout_ns);
    }

    public static function gpioLineRequestReadEdgeEvents(GPIOLineRequest $request, GPIOEdgeEventBuffer $buffer, int $max_events): int
    {
        if ($max_events < 0) {
            return -1;
        }

        $buffer->events = [];
        $buffer->num_events = 0;

        $to_read = min($max_events, $buffer->capacity);
        if ($to_read === 0) {
            return 0;
        }

        $raw = System::read($request->fd, $to_read * 48);
        if (! is_string($raw)) {
            return -1;
        }

        $event_count = intdiv(strlen($raw), 48);
        if ($event_count === 0) {
            return 0;
        }

        $events = [];
        for ($index = 0; $index < $event_count; $index++) {
            $uapi = GPIOV2LineEvent::fromBuffer(substr($raw, $index * 48, 48));
            if (! $uapi instanceof GPIOV2LineEvent) {
                return -1;
            }

            $events[] = new GPIOEdgeEvent(
                event_type: $uapi->id,
                timestamp_ns: $uapi->timestamp_ns,
                line_offset: $uapi->offset,
                global_seqno: $uapi->seqno,
                line_seqno: $uapi->line_seqno,
            );
        }

        $buffer->events = $events;
        $buffer->num_events = count($events);

        return $buffer->num_events;
    }

    private static function indexForOffset(GPIOLineRequest $request, int $offset): ?int
    {
        $count = min($request->num_lines, count($request->offsets));

        for ($index = 0; $index < $count; $index++) {
            if ($request->offsets[$index] === $offset) {
                return $index;
            }
        }

        return null;
    }

    private static function assignMaskBit(int $value, int $bit, bool $state): int
    {
        $mask = static::bitMask($bit);

        if ($state) {
            return $value | $mask;
        }

        return $value & (~$mask);
    }

    private static function isMaskBitSet(int $value, int $bit): bool
    {
        return (bool) ($value & static::bitMask($bit));
    }

    private static function bitMask(int $bit): int
    {
        return $bit === 63
            ? PHP_INT_MIN
            : (1 << $bit);
    }
}
