<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

use Microscrap\Bindings\GPIO\Enums\GPIOV2LineAttrId;

/**
 * struct gpio_v2_line_attribute  (linux/gpio.h)
 *
 * Binary layout (16 bytes, little-endian):
 *   offset  0 : __u32 id
 *   offset  4 : __u32 padding
 *   offset  8 : union { __u64 flags | __u64 values | __u32 debounce_period_us }
 */
final readonly class GPIOV2LineAttributes
{
    public function __construct(
        public GPIOV2LineAttrId $id,
        public int              $padding,
        public ?int             $flags              = null,
        public ?int             $values             = null,
        public ?int             $debounce_period_us = null,
    ) {}

    /**
     * Unpack a single gpio_v2_line_attribute from a binary buffer.
     *
     * @param string $buffer Full binary buffer (e.g. the gpio_v2_line_info blob).
     * @param int    $offset Byte offset of this attribute within $buffer.
     */
    public static function fromBuffer(string $buffer, int $offset = 0): self
    {
        $id      = GPIOV2LineAttrId::from(unpack('V', substr($buffer, $offset + 0, 4))[1]);
        $padding = unpack('V', substr($buffer, $offset + 4, 4))[1];
        $raw     = unpack('P', substr($buffer, $offset + 8, 8))[1];

        $flags              = null;
        $values             = null;
        $debounce_period_us = null;

        match ($id) {
            GPIOV2LineAttrId::FLAGS         => $flags              = $raw,
            GPIOV2LineAttrId::OUTPUT_VALUES => $values             = $raw,
            GPIOV2LineAttrId::DEBOUNCE      => $debounce_period_us = $raw & 0xFFFFFFFF,
        };

        return new self($id, $padding, $flags, $values, $debounce_period_us);
    }
}
