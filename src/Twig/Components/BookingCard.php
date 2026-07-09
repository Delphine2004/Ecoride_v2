<?php

namespace App\Twig\Components;

use App\Entity\Booking;
use App\Entity\User;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsTwigComponent]
final class BookingCard
{
    use DefaultActionTrait;

    public ?User $user;

    public Booking $booking;

    public ?string $message = null;
}
