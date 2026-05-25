<?php

namespace Microscrap\Bindings\GPIO\Enums;

enum StatMode: int
{
    case S_IFMT  = 0170000;
    case S_IFLNK = 0120000;
    case S_IFCHR = 0020000;
}
