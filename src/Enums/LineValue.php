<?php

namespace Microscrap\Bindings\GPIO\Enums;

enum LineValue: int
{
    case ERROR = -1;
    case INACTIVE = 0;
    case ACTIVE = 1;
}
