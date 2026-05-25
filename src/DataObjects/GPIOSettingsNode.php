<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

/**
 * struct settings_node
 */
class GPIOSettingsNode
{
    public ?GPIOSettingsNode $prev = null;
    public ?GPIOSettingsNode $next = null;
    public GPIOLineSettings $settings;
    public int $refcnt = 0;

    public function __construct(GPIOLineSettings $settings)
    {
        $this->settings = $settings;
    }
}
