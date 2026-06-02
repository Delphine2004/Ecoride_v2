<?php

namespace App\Enum;

enum CarPower: string
{
    case DIESEL = "Diesel";
    case ESSENCE = "Essence";
    case ELECTRIC = "Electrique";
    case HYBRID = "Hybrid";
    case NA = "N-A";
}
