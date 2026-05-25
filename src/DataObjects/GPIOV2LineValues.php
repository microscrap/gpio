<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

/**
 * struct gpio_v2_line_values (linux/gpio.h)
 *
 * Binary layout (16 bytes, little-endian):
 *   offset 0 : __u64 bits
 *   offset 8 : __u64 mask
 */
final class GPIOV2LineValues
{
    public function __construct(
        public int $bits = 0,
        public int $mask = 0,
    ) {}

    public function toBuffer(): string
    {
        return pack('P', $this->bits) . pack('P', $this->mask);
    }

    public static function fromBuffer(string $buffer): ?self
    {
        if (strlen($buffer) < 16) {
            return null;
        }

        return new self(
            bits: unpack('P', substr($buffer, 0, 8))[1],
            mask: unpack('P', substr($buffer, 8, 8))[1],
        );
    }
}
