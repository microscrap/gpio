<?php

namespace Microscrap\Bindings\GPIO\Enums;

enum LineClock: int
{
    case MONOTONIC = 1;
    case REALTIME = 2;
    case HTE = 3;
}
