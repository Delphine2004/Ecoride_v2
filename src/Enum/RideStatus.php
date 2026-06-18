<?php

namespace App\Enum;

enum RideStatus: string
{
    case CONFIRMED = "Confirmee";
    case CANCELLED = "Annule";
    case RUNNING = "En cours";
    case PENDING = "En attente";
    case COMPLETED = "Termine";
}
