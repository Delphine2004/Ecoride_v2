<?php

namespace App\Twig\Components;

use App\Entity\Ride;
use App\Service\RideService;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

#[AsLiveComponent]
final class RideActions
{
    use DefaultActionTrait;

    #[LiveProp]
    public Ride $ride;

    public function __construct(
        private RideService $rideService
    ) {}

    #[LiveAction]
    public function cancel(): void
    {
        $this->rideService->cancel($this->ride);
    }

    #[LiveAction]
    public function start(): void
    {
        $this->rideService->start($this->ride);
    }

    #[LiveAction]
    public function stop(): void
    {
        $this->rideService->stop($this->ride);
    }
}
