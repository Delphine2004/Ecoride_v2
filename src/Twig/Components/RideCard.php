<?php

namespace App\Twig\Components;

use App\Entity\Ride;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsTwigComponent]
final class RideCard
{
    use DefaultActionTrait;

    #[LiveProp]
    public Ride $ride;
}
