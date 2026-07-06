<?php

namespace Microscrap\Bindings\GPIO\Enums;

enum LineDirection: int
{
    case AS_IS = 1;
    case INPUT = 2;
    case OUTPUT = 3;
}
