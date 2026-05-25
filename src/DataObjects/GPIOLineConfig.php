<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

use Microscrap\Bindings\GPIO\Enums\LineValue;

/**
 * struct gpiod_line_config
 */
class GPIOLineConfig
{
    /**
     * @var array<int, GPIOPerLineConfig>
     */
    public array $line_configs = [];

    public int $num_configs = 0;

    /**
     * @var array<int, LineValue>
     */
    public array $output_values = [];

    public int $num_output_values = 0;

    public ?GPIOSettingsNode $sref_list = null;

    public function __construct()
    {
        for ($i = 0; $i < 64; $i++) { // GPIO_V2_LINES_MAX
            $this->line_configs[$i] = new GPIOPerLineConfig();
            $this->output_values[$i] = LineValue::Inactive;
        }
    }
}
