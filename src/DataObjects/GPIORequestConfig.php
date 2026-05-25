<?php

namespace Microscrap\Bindings\GPIO\DataObjects;

/**
 * struct gpiod_request_config
 */
class GPIORequestConfig
{
    // GPIO_MAX_NAME_SIZE (32 bytes in kernel uAPI consumer field)
    public string $consumer = '';
    public int $event_buffer_size = 0;
}
