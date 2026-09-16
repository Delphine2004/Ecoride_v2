<?php

namespace App\Twig\Components;

use App\Entity\Ride;
use App\Entity\User;

use App\Service\CreationService;
use App\Service\CancelationService;
use App\Service\StateService;

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
        private CreationService $creationService,
        private CancelationService $cancelationService,
        private StateService $stateService,
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
            $this->message = 'Vous devez être connecté pour réserver.';
            return;
        }

        try {
            $this->creationService->bookRide($this->ride, $user);
            $this->message = 'Réservation confirmée.';
        } catch (\LogicException $e) {
            $this->message = $e->getMessage();
        }
    }


    #[LiveAction]
    public function cancel(): void
    {

        $user = $this->getUser();

        if (!$user instanceof User) {
            $this->message = 'Vous devez être connecté.';
            return;
        }
        try {
            $this->cancelationService->cancelRide($this->ride, $this->getUser());
            $this->message = 'Annulation confirmée.';
        } catch (\LogicException $e) {
            $this->message = $e->getMessage();
        }
    }

    #[LiveAction]
    public function start(): void
    {

        $user = $this->getUser();

        if (!$user instanceof User) {
            $this->message = 'Vous devez être connecté.';
            return;
        }
        try {
            $this->stateService->startRide($this->ride);
            $this->message = 'Trajet démarré.';
        } catch (\LogicException $e) {
            $this->message = $e->getMessage();
        }
    }

    #[LiveAction]
    public function stop(): void
    {

        $user = $this->getUser();

        if (!$user instanceof User) {
            $this->message = 'Vous devez être connecté.';
            return;
        }
        try {
            $this->stateService->stopRide($this->ride);
            $this->message = 'Trajet arrêté.';
        } catch (\LogicException $e) {
            $this->message = $e->getMessage();
        }
    }
}
