<?php

namespace Microscrap\Bindings\GPIO\Enums;

enum LineDrive: int
{
    case PUSH_PULL = 1;
    case OPEN_DRAIN = 2;
    case OPEN_SOURCE = 3;
}
