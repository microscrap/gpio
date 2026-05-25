<?php

namespace Microscrap\Bindings\GPIO\Enums;

enum LineEdge: int
{
    case None = 1;
    case Rising = 2;
    case Falling = 3;
    case Both = 4;
}
