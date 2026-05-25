<?php

use Microscrap\Bindings\GPIO\DataObjects\GPIOEdgeEvent;
use Microscrap\Bindings\GPIO\DataObjects\GPIOEdgeEventBuffer;
use Microscrap\Bindings\GPIO\EdgeEventBuffer;

if(!function_exists('gpiod_edge_event_buffer_new'))
{
    function gpiod_edge_event_buffer_new(int $capacity = 64): ?GPIOEdgeEventBuffer
    {
        return EdgeEventBuffer::gpioEdgeEventBufferNew($capacity);
    }
}

if(!function_exists('gpiod_edge_event_buffer_get_capacity'))
{
    function gpiod_edge_event_buffer_get_capacity(GPIOEdgeEventBuffer $buffer): int
    {
        return EdgeEventBuffer::gpioEdgeEventBufferGetCapacity($buffer);
    }
}

if(!function_exists('gpiod_edge_event_buffer_get_event'))
{
    function gpiod_edge_event_buffer_get_event(GPIOEdgeEventBuffer $buffer, int $index): ?GPIOEdgeEvent
    {
        return EdgeEventBuffer::gpioEdgeEventBufferGetEvent($buffer, $index);
    }
}

if(!function_exists('gpiod_edge_event_buffer_get_num_events'))
{
    function gpiod_edge_event_buffer_get_num_events(GPIOEdgeEventBuffer $buffer): int
    {
        return EdgeEventBuffer::gpioEdgeEventBufferGetNumEvents($buffer);
    }
}
