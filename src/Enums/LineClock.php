<?php

namespace Microscrap\Bindings\GPIO\Enums;

enum LineClock: int
{
    case Monotonic = 1;
    case Realtime = 2;
    case Hte = 3;
}
