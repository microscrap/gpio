<?php

namespace Microscrap\Bindings\GPIO\Enums;

enum LineDrive: int
{
    case PushPull = 1;
    case OpenDrain = 2;
    case OpenSource = 3;
}
