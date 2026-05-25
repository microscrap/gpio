<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

use Microscrap\Bindings\GPIO\Enums\GPIOV2LineFlag;

/**
 * struct gpio_v2_line_info  (linux/gpio.h)
 *
 * Binary layout (256 bytes, little-endian):
 *   offset   0 : __u8  name[32]
 *   offset  32 : __u8  consumer[32]
 *   offset  64 : __u32 offset
 *   offset  68 : __u32 num_attrs
 *   offset  72 : __u64 flags
 *   offset  80 : struct gpio_v2_line_attribute attrs[10]  (10 × 16 bytes = 160 bytes)
 *   offset 240 : __u32 padding[4]
 */
final readonly class GPIOV2LineInfo
{
    /**
     * @param GPIOV2LineAttributes[] $attrs
     */
    public function __construct(
        public string $name,
        public string $consumer,
        public int    $offset,
        public int    $num_attrs,
        public int    $flags,
        public array  $attrs,
    ) {}

    /**
     * Unpack a 256-byte kernel gpio_v2_line_info buffer into this object.
     */
    public static function fromBuffer(string $buffer): self
    {
        $name      = rtrim(substr($buffer, 0, 32), "\0");   // __u8 name[32]
        $consumer  = rtrim(substr($buffer, 32, 32), "\0");  // __u8 consumer[32]
        $offset    = unpack('V', substr($buffer, 64, 4))[1]; // __u32 offset
        $num_attrs = unpack('V', substr($buffer, 68, 4))[1]; // __u32 num_attrs
        $flags     = unpack('P', substr($buffer, 72, 8))[1]; // __u64 flags

        $attrs = [];
        $count = min($num_attrs, 10); // GPIO_V2_LINE_NUM_ATTRS_MAX = 10
        for ($i = 0; $i < $count; $i++) {
            $attrs[] = GPIOV2LineAttributes::fromBuffer($buffer, 80 + $i * 16);
        }

        return new self($name, $consumer, $offset, $num_attrs, $flags, $attrs);
    }

    /**
     * Decode the flags bitmask into an array of GPIOV2LineFlag cases.
     *
     * @return GPIOV2LineFlag[]
     */
    public function flags(): array
    {
        $results = [];

        foreach (GPIOV2LineFlag::cases() as $flag) {
            if ($this->flags & $flag->value) {
                $results[] = $flag;
            }
        }

        return $results;
    }

    public function hasFlag(GPIOV2LineFlag $flag): bool
    {
        return (bool) ($this->flags & $flag->value);
    }
}
