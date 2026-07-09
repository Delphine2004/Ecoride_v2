<?php

namespace App\Twig\Components;

use App\Entity\Ride;
use App\Entity\User;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsTwigComponent]
final class RideCard
{
    use DefaultActionTrait;

    public ?User $user;

    public Ride $ride;

    public ?string $message = null;
}
