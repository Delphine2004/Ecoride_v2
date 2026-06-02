<?php

namespace App\Enum;

enum RideStatus: string
{
    case AVAILABLE = "Disponible";
    case FULL = "Complet";
    case CANCELLED = "Annule";
    case RUNNING = "En cours";
    case PENDING = "En attente";
    case COMPLETED = "Termine";
}
