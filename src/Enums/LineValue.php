<?php

namespace Microscrap\Bindings\GPIO\Enums;

enum LineValue: int
{
    case Error = -1;
    case Inactive = 0;
    case Active = 1;
}
