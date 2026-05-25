<?php

namespace Microscrap\Bindings\GPIO\Enums;

enum EdgeEventType: int
{
    case RISING_EDGE = 1;
    case FALLING_EDGE = 2;
}
