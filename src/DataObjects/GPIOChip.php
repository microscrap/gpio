<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

/**
 * struct gpiod_chip
 */
final readonly class GPIOChip
{
    public function __construct(
        public int $fd,
        public string $path,
    ) {}
}