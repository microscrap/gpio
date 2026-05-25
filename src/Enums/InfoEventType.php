<?php

namespace Microscrap\Bindings\GPIO\Enums;

enum InfoEventType: int
{
    case LINE_REQUESTED     = 1;
    case LINE_RELEASED      = 2;
    case LINE_CONFIG_CHANGED = 3;
}
