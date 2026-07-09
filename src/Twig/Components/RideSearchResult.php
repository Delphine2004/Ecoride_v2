<?php

namespace App\Twig\Components;

use App\Entity\Ride;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

#[AsTwigComponent]
final class RideSearchResult
{
    use DefaultActionTrait;

    public Ride $ride;

    public ?string $message = null;
}
