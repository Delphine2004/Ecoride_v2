<?php

namespace App\Twig\Components;

use App\Entity\Ride;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsTwigComponent]
final class RideCard
{
    use DefaultActionTrait;

    public Ride $ride;

    public ?string $message = null;
}
