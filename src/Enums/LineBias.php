<?php

namespace Microscrap\Bindings\GPIO\Enums;

enum LineBias: int
{
    case AsIs = 1;
    case Unknown = 2;
    case Disabled = 3;
    case PullUp = 4;
    case PullDown = 5;
}
