<?php

namespace App\Twig\Components;

use App\Entity\Booking;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsTwigComponent]
final class BookingCard
{
    use DefaultActionTrait;

    public Booking $booking;

    public ?string $message = null;
}
