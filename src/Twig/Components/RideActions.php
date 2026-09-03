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

    #[LiveProp]
    public ?string $message = null;

    public function getUser(): ?User
    {
        return $this->security->getUser();
    }

    #[LiveAction]
    public function book(): void
    {


        $user = $this->getUser();

        if (!$user instanceof User) {
            $this->message = 'Vous devez être connecté pour réserver un trajet.';
            return;
        }

        try {
            $this->rideService->book($this->ride, $user);
            $this->message = 'Réservation confirmée.';
        } catch (\LogicException $e) {
            $this->message = $e->getMessage();
        }
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
