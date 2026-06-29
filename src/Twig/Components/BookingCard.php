<?php

namespace App\Twig\Components;

use App\Entity\Booking;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsTwigComponent]
final class BookingCard
{
    use DefaultActionTrait;

    #[LiveProp]
    public Booking $booking;

    #[LiveProp]
    public ?string $message = null;
}
