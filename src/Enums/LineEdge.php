<?php

namespace Microscrap\Bindings\GPIO\Enums;

enum LineEdge: int
{
    case NONE = 1;
    case RISING = 2;
    case FALLING = 3;
    case BOTH = 4;
}
