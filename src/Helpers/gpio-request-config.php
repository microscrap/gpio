<?php

use Microscrap\Bindings\GPIO\DataObjects\GPIORequestConfig;
use Microscrap\Bindings\GPIO\RequestConfig;

if(!function_exists('gpiod_request_config_new'))
{
    function gpiod_request_config_new(): GPIORequestConfig
    {
        return RequestConfig::gpioRequestConfigNew();
    }
}

if(!function_exists('gpiod_request_config_set_consumer'))
{
    function gpiod_request_config_set_consumer(GPIORequestConfig $config, string $consumer): int
    {
        return RequestConfig::gpioRequestConfigSetConsumer($config, $consumer);
    }
}

if(!function_exists('gpiod_request_config_get_consumer'))
{
    function gpiod_request_config_get_consumer(GPIORequestConfig $config): string
    {
        return RequestConfig::gpioRequestConfigGetConsumer($config);
    }
}

if(!function_exists('gpiod_request_config_set_event_buffer_size'))
{
    function gpiod_request_config_set_event_buffer_size(GPIORequestConfig $config, int $event_buffer_size): int
    {
        return RequestConfig::gpioRequestConfigSetEventBufferSize($config, $event_buffer_size);
    }
}

if(!function_exists('gpiod_request_config_get_event_buffer_size'))
{
    function gpiod_request_config_get_event_buffer_size(GPIORequestConfig $config): int
    {
        return RequestConfig::gpioRequestConfigGetEventBufferSize($config);
    }
}
