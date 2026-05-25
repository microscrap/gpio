<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

/**
 * struct gpiod_line_request
 */
final readonly class GPIOLineRequest
{
    public function __construct(
        public string $chip_name,
        /** @var array<int, int> */
        public array $offsets,
        public int $num_lines,
        public int $fd,
    ) {}
}
