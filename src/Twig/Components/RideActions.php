<?php

namespace App\Twig\Components;

use App\Entity\Ride;
use App\Entity\User;
use App\Service\RideService;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\Bundle\SecurityBundle\Security;

#[AsLiveComponent]
final class RideActions
{
    use DefaultActionTrait;

    public function __construct(
        private Security $security,
        private RideService $rideService
    ) {}

    #[LiveProp]
    public Ride $ride;

    public function getUser(): ?User
    {
        return $this->security->getUser();
    }


    #[LiveAction]
    public function cancel(): void
    {
        $this->rideService->cancel($this->ride, $this->getUser());
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
