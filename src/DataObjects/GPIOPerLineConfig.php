<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

/**
 * struct per_line_config
 */
class GPIOPerLineConfig
{
    public int $offset = 0;
    public ?GPIOSettingsNode $node = null;
}
