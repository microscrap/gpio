<?php

namespace Microscrap\Bindings\GPIO\Enums;

enum LineDirection: int
{
    case AsIs = 1;
    case Input = 2;
    case Output = 3;
}
