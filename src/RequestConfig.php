<?php

namespace Microscrap\Bindings\GPIO;

use Microscrap\Bindings\GPIO\DataObjects\GPIORequestConfig;

class RequestConfig
{
    public static function gpioRequestConfigNew(): GPIORequestConfig
    {
        return new GPIORequestConfig();
    }

    public static function gpioRequestConfigSetConsumer(GPIORequestConfig $config, string $consumer): int
    {
        // GPIO_MAX_NAME_SIZE = 32 in linux/gpio.h.
        $config->consumer = substr($consumer, 0, 32);

        return 0;
    }

    public static function gpioRequestConfigGetConsumer(GPIORequestConfig $config): string
    {
        return $config->consumer;
    }

    public static function gpioRequestConfigSetEventBufferSize(GPIORequestConfig $config, int $event_buffer_size): int
    {
        if ($event_buffer_size < 0) {
            return -1;
        }

        $config->event_buffer_size = $event_buffer_size;

        return 0;
    }

    public static function gpioRequestConfigGetEventBufferSize(GPIORequestConfig $config): int
    {
        return $config->event_buffer_size;
    }
}
