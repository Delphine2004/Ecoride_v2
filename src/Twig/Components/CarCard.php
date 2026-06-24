<?php

namespace App\Twig\Components;

use App\Entity\Car;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsLiveComponent]
final class CarCard
{
    use DefaultActionTrait;

    #[LiveProp]
    public Car $car;
}
