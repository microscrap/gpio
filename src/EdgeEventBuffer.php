<?php

namespace Microscrap\Bindings\GPIO;

use Microscrap\Bindings\GPIO\DataObjects\GPIOEdgeEvent;
use Microscrap\Bindings\GPIO\DataObjects\GPIOEdgeEventBuffer;

class EdgeEventBuffer
{
    public static function gpioEdgeEventBufferNew(int $capacity = 64): ?GPIOEdgeEventBuffer
    {
        if ($capacity <= 0 || $capacity > 1024) {
            return null;
        }

        return new GPIOEdgeEventBuffer($capacity);
    }

    public static function gpioEdgeEventBufferGetCapacity(GPIOEdgeEventBuffer $buffer): int
    {
        return $buffer->capacity;
    }

    public static function gpioEdgeEventBufferGetEvent(GPIOEdgeEventBuffer $buffer, int $index): ?GPIOEdgeEvent
    {
        if ($index < 0 || $index >= $buffer->num_events) {
            return null;
        }

        return $buffer->events[$index] ?? null;
    }

    public static function gpioEdgeEventBufferGetNumEvents(GPIOEdgeEventBuffer $buffer): int
    {
        return $buffer->num_events;
    }
}
