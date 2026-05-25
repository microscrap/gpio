<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

use Microscrap\Bindings\GPIO\Enums\EdgeEventType;

/**
 * struct gpio_v2_line_event (linux/gpio.h)
 *
 * Binary layout (48 bytes, little-endian):
 *   offset  0 : __u64 timestamp_ns
 *   offset  8 : __u32 id
 *   offset 12 : __u32 offset
 *   offset 16 : __u32 seqno
 *   offset 20 : __u32 line_seqno
 *   offset 24 : __u32 padding[6]
 */
final readonly class GPIOV2LineEvent
{
    public function __construct(
        public int $timestamp_ns,
        public EdgeEventType $id,
        public int $offset,
        public int $seqno,
        public int $line_seqno,
    ) {}

    public static function fromBuffer(string $buffer): ?self
    {
        if (strlen($buffer) < 48) {
            return null;
        }

        $id = EdgeEventType::tryFrom(unpack('V', substr($buffer, 8, 4))[1]);
        if (! $id instanceof EdgeEventType) {
            return null;
        }

        return new self(
            timestamp_ns: unpack('P', substr($buffer, 0, 8))[1],
            id: $id,
            offset: unpack('V', substr($buffer, 12, 4))[1],
            seqno: unpack('V', substr($buffer, 16, 4))[1],
            line_seqno: unpack('V', substr($buffer, 20, 4))[1],
        );
    }
}
