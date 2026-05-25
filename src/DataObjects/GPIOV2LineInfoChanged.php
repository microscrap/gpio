<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

use Microscrap\Bindings\GPIO\Enums\InfoEventType;

/**
 * struct gpio_v2_line_info_changed (linux/gpio.h)
 *
 * Binary layout (288 bytes, little-endian):
 *   offset   0 : struct gpio_v2_line_info info (256 bytes)
 *   offset 256 : __u64 timestamp_ns
 *   offset 264 : __u32 event_type
 *   offset 268 : __u32 padding[5]
 */
final readonly class GPIOV2LineInfoChanged
{
    public function __construct(
        public GPIOV2LineInfo $info,
        public int $timestamp_ns,
        public InfoEventType $event_type,
    ) {}

    /**
     * Unpack a 288-byte kernel gpio_v2_line_info_changed buffer.
     */
    public static function fromBuffer(string $buffer): ?self
    {
        if (strlen($buffer) < 288) {
            return null;
        }

        $info = GPIOV2LineInfo::fromBuffer(substr($buffer, 0, 256));
        $timestamp_ns = unpack('P', substr($buffer, 256, 8))[1];
        $event_type_raw = unpack('V', substr($buffer, 264, 4))[1];
        $event_type = InfoEventType::tryFrom($event_type_raw);
        if ($event_type === null) {
            return null;
        }

        return new self($info, $timestamp_ns, $event_type);
    }
}
