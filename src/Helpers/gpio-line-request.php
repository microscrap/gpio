<?php

use Microscrap\Bindings\GPIO\DataObjects\GPIOLineConfig;
use Microscrap\Bindings\GPIO\DataObjects\GPIOEdgeEventBuffer;
use Microscrap\Bindings\GPIO\DataObjects\GPIOLineRequest;
use Microscrap\Bindings\GPIO\Enums\LineValue;
use Microscrap\Bindings\GPIO\LineRequest;

if(!function_exists('gpiod_line_request_release'))
{
    function gpiod_line_request_release(GPIOLineRequest $request): int
    {
        return LineRequest::gpioLineRequestRelease($request);
    }
}

if(!function_exists('gpiod_line_request_get_fd'))
{
    function gpiod_line_request_get_fd(GPIOLineRequest $request): int
    {
        return LineRequest::gpioLineRequestGetFD($request);
    }
}

if(!function_exists('gpiod_line_request_get_chip_name'))
{
    function gpiod_line_request_get_chip_name(GPIOLineRequest $request): string
    {
        return LineRequest::gpioLineRequestGetChipName($request);
    }
}

if(!function_exists('gpiod_line_request_get_num_requested_lines'))
{
    function gpiod_line_request_get_num_requested_lines(GPIOLineRequest $request): int
    {
        return LineRequest::gpioLineRequestGetNumRequestedLines($request);
    }
}

if(!function_exists('gpiod_line_request_get_requested_offsets'))
{
    /**
     * @return array<int, int>
     */
    function gpiod_line_request_get_requested_offsets(GPIOLineRequest $request, int $max_offsets): array
    {
        return LineRequest::gpioLineRequestGetRequestedOffsets($request, $max_offsets);
    }
}

if(!function_exists('gpiod_line_request_get_values'))
{
    /**
     * @return array<int, LineValue>|null
     */
    function gpiod_line_request_get_values(GPIOLineRequest $request): ?array
    {
        return LineRequest::gpioLineRequestGetValues($request);
    }
}

if(!function_exists('gpiod_line_request_get_value'))
{
    function gpiod_line_request_get_value(GPIOLineRequest $request, int $offset): ?LineValue
    {
        return LineRequest::gpioLineRequestGetValue($request, $offset);
    }
}

if(!function_exists('gpiod_line_request_get_values_subset'))
{
    /**
     * @param array<int, int> $offsets
     * @return array<int, LineValue>|null
     */
    function gpiod_line_request_get_values_subset(GPIOLineRequest $request, array $offsets): ?array
    {
        return LineRequest::gpioLineRequestGetValuesSubset($request, $offsets);
    }
}

if(!function_exists('gpiod_line_request_set_values'))
{
    /**
     * @param array<int, LineValue|int> $values
     */
    function gpiod_line_request_set_values(GPIOLineRequest $request, array $values): int
    {
        return LineRequest::gpioLineRequestSetValues($request, $values);
    }
}

if(!function_exists('gpiod_line_request_set_value'))
{
    function gpiod_line_request_set_value(GPIOLineRequest $request, int $offset, LineValue|int $value): int
    {
        return LineRequest::gpioLineRequestSetValue($request, $offset, $value);
    }
}

if(!function_exists('gpiod_line_request_set_values_subset'))
{
    /**
     * @param array<int, int> $offsets
     * @param array<int, LineValue|int> $values
     */
    function gpiod_line_request_set_values_subset(GPIOLineRequest $request, array $offsets, array $values): int
    {
        return LineRequest::gpioLineRequestSetValuesSubset($request, $offsets, $values);
    }
}

if(!function_exists('gpiod_line_request_reconfigure_lines'))
{
    function gpiod_line_request_reconfigure_lines(GPIOLineRequest $request, GPIOLineConfig $config): int
    {
        return LineRequest::gpioLineRequestReconfigureLines($request, $config);
    }
}

if(!function_exists('gpiod_line_request_wait_edge_events'))
{
    function gpiod_line_request_wait_edge_events(GPIOLineRequest $request, int $timeout_ns): ?int
    {
        return LineRequest::gpioLineRequestWaitEdgeEvents($request, $timeout_ns);
    }
}

if(!function_exists('gpiod_line_request_read_edge_events'))
{
    function gpiod_line_request_read_edge_events(GPIOLineRequest $request, GPIOEdgeEventBuffer $buffer, int $max_events): int
    {
        return LineRequest::gpioLineRequestReadEdgeEvents($request, $buffer, $max_events);
    }
}
