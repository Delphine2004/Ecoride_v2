<?php

namespace App\Enum;

enum BookingStatus: string
{
    case CONFIRMED = "Confirmee";
    case CANCELLED = "Annulee";
    case RUNNING = "En cours";
    case PENDING = "En attente";
    case REPORTED = "Signalee";
    case FINALIZED = "Finalisee";
    case REFUNDED = "Remboursee";
}
