<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

/**
 * struct gpiod_chip_info
 */
final readonly class GPIOChipInfo
{
    public function __construct(
        public int $num_lines,
        public string $name,
        public string $label,
    ) {}
}
