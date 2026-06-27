<?php

namespace App\Twig\Components;

use App\Entity\Ride;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
final class RideSearchResult
{

    public Ride $ride;
}
