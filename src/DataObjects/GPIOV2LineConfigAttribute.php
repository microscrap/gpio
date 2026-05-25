<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

use Microscrap\Bindings\GPIO\Enums\GPIOV2LineAttrId;

/**
 * struct gpio_v2_line_config_attribute (linux/gpio.h)
 *
 * Binary layout (24 bytes, little-endian):
 *   offset  0 : struct gpio_v2_line_attribute attr (16 bytes)
 *   offset 16 : __u64 mask
 */
final class GPIOV2LineConfigAttribute
{
    public function __construct(
        public GPIOV2LineAttrId $id,
        public int $mask = 0,
        public ?int $flags = null,
        public ?int $values = null,
        public ?int $debounce_period_us = null,
    ) {}

    /**
     * Pack this attribute into an existing binary buffer at byte $offset.
     */
    public function toBuffer(int $offset, string &$buffer): void
    {
        if (strlen($buffer) < $offset) {
            $buffer = str_pad($buffer, $offset, "\0");
        }

        $union = match ($this->id) {
            GPIOV2LineAttrId::FLAGS => pack('P', $this->flags ?? 0),
            GPIOV2LineAttrId::OUTPUT_VALUES => pack('P', $this->values ?? 0),
            GPIOV2LineAttrId::DEBOUNCE => pack('V', $this->debounce_period_us ?? 0) . "\0\0\0\0",
        };

        $serialized = pack('V', $this->id->value)
            . pack('V', 0)
            . $union
            . pack('P', $this->mask);

        $buffer = substr_replace($buffer, $serialized, $offset, 24);
    }
}
