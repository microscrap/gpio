<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

/**
 * struct gpio_v2_line_config (linux/gpio.h)
 *
 * Binary layout (272 bytes, little-endian):
 *   offset   0 : __u64 flags
 *   offset   8 : __u32 num_attrs
 *   offset  12 : __u32 padding[5]
 *   offset  32 : struct gpio_v2_line_config_attribute attrs[10] (10 x 24 bytes)
 */
final class GPIOV2LineConfig
{
    /**
     * @param GPIOV2LineConfigAttribute[] $attrs
     */
    public function __construct(
        public int $flags = 0,
        public int $num_attrs = 0,
        public array $attrs = [],
    ) {}

    /**
     * Pack this object into a 272-byte kernel gpio_v2_line_config buffer.
     */
    public function toBuffer(): string
    {
        $num_attrs = min(10, min($this->num_attrs, count($this->attrs))); // GPIO_V2_LINE_NUM_ATTRS_MAX

        $buffer = pack('P', $this->flags)
            . pack('V', $num_attrs)
            . str_repeat("\0", 20)
            . str_repeat("\0", 240);

        for ($index = 0; $index < $num_attrs; $index++) {
            $this->attrs[$index]->toBuffer(32 + ($index * 24), $buffer);
        }

        return substr($buffer, 0, 272);
    }
}
