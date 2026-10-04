<?php

declare(strict_types=1);

use Microscrap\Bindings\GPIO\DataObjects\GPIOChip;
use Microscrap\Bindings\GPIO\DataObjects\GPIOInfoEvent;
use Microscrap\Bindings\GPIO\DataObjects\GPIOLineRequest;
use Microscrap\Bindings\GPIO\Enums\EdgeEventType;
use Microscrap\Bindings\GPIO\Enums\InfoEventType;

/** One struct gpio_v2_line_event (48 bytes): timestamp_ns, id, offset, seqno, line_seqno, padding. */
function lineEvent(EdgeEventType $type, int $offset, int $seqno, int $lineSeqno, int $timestampNs): string
{
    return pack('PVVVV', $timestampNs, $type->value, $offset, $seqno, $lineSeqno) . str_repeat("\0", 24);
}

/** One struct gpio_v2_line_info_changed (288 bytes) around a gpio_v2_line_info for $offset. */
function lineInfoChanged(InfoEventType $type, string $name, int $offset, int $timestampNs): string
{
    $info = str_pad($name, 32, "\0") . str_repeat("\0", 32) . pack('VVP', $offset, 0, 0) . str_repeat("\0", 256 - 80);

    return $info . pack('PV', $timestampNs, $type->value) . str_repeat("\0", 20);
}

it('refuses to open a character device outside the GPIO subsystem', function () {
    expect(gpiod_check_gpiochip_device('/dev/null'))->toBeFalse()
        ->and(gpiod_chip_open('/dev/null'))->toBeNull()
        ->and(gpiod_chip_open('/dev/no-such-gpiochip'))->toBeNull();
});

it('waits for and reads edge events from the request fd', function () {
    [$read, $write] = fifoPair();
    $request = new GPIOLineRequest('gpiochip0', [17, 27], 2, $read);
    $buffer = gpiod_edge_event_buffer_new(4);

    expect(gpiod_line_request_wait_edge_events($request, 0))->toBe(0);

    posix_write($write, $event = lineEvent(EdgeEventType::RISING_EDGE, 27, 5, 2, 123_456_789), 48);

    expect(gpiod_line_request_wait_edge_events($request, 0))->toBe(1)
        ->and(gpiod_line_request_read_edge_events($request, $buffer, 4))->toBe(1);

    $edge = gpiod_edge_event_buffer_get_event($buffer, 0);

    expect(gpiod_edge_event_get_event_type($edge))->toBe(EdgeEventType::RISING_EDGE)
        ->and(gpiod_edge_event_get_line_offset($edge))->toBe(27)
        ->and(gpiod_edge_event_get_global_seqno($edge))->toBe(5)
        ->and(gpiod_edge_event_get_line_seqno($edge))->toBe(2)
        ->and(gpiod_edge_event_get_timestamp_ns($edge))->toBe(123_456_789);
});

it('reports -1 when the request fd cannot be read', function () {
    $request = new GPIOLineRequest('gpiochip0', [17], 1, -1);

    expect(gpiod_line_request_read_edge_events($request, gpiod_edge_event_buffer_new(1), 1))->toBe(-1)
        ->and(posi_errno())->toBe(EBADF);
});

it('closes the request fd on release', function () {
    [$read] = fifoPair();
    $request = new GPIOLineRequest('gpiochip0', [17], 1, $read);

    expect(gpiod_line_request_release($request))->toBe(0)
        ->and(fcntl($read, F_GETFD, null, $flags))->toBe(-1);
});

it('waits for and reads line info events from the chip fd', function () {
    [$read, $write] = fifoPair();
    $chip = new GPIOChip($read, '/dev/gpiochip0');

    expect(gpiod_chip_wait_info_event($chip, 0))->toBe(0);

    posix_write($write, lineInfoChanged(InfoEventType::LINE_REQUESTED, 'GPIO17', 17, 42), 288);

    expect(gpiod_chip_wait_info_event($chip, 0))->toBe(1);

    $event = gpiod_chip_read_info_event($chip);

    expect($event)->toBeInstanceOf(GPIOInfoEvent::class)
        ->and(gpiod_info_event_get_event_type($event))->toBe(InfoEventType::LINE_REQUESTED)
        ->and(gpiod_line_info_get_offset(gpiod_info_event_get_line_info($event)))->toBe(17)
        ->and(gpiod_line_info_get_name(gpiod_info_event_get_line_info($event)))->toBe('GPIO17');
});
