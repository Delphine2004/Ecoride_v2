<?php

namespace App\Twig\Components;

use App\Entity\Booking;
use App\Entity\User;
use App\Service\BookingService;
use App\Service\EmailService;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\Bundle\SecurityBundle\Security;

#[AsLiveComponent]
final class BookingActions
{
    use DefaultActionTrait;

    public function __construct(
        private Security $security,
        private BookingService $bookingService,
        private EmailService $emailService
    ) {}

    #[LiveProp]
    public Booking $booking;

    #[LiveProp]
    public ?string $message = null;

    public function getUser(): ?User
    {
        return $this->security->getUser();
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
            $this->bookingService->cancel($this->booking, $this->getUser());
            $this->message = 'Annulation confirmée.';
        } catch (\LogicException $e) {
            $this->message = $e->getMessage();
        }
    }

    #[LiveAction]
    public function report(): void
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            $this->message = 'Vous devez être connecté.';
            return;
        }
        try {
            $this->bookingService->report($this->booking);
            $this->message = 'Réservation signalée.';
        } catch (\LogicException $e) {
            $this->message = $e->getMessage();
        }
    }

    #[LiveAction]
    public function finalize(): void
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            $this->message = 'Vous devez être connecté.';
            return;
        }
        try {
            $this->bookingService->finalize($this->booking);
            $this->message = 'Réservation finalisée.';
        } catch (\LogicException $e) {
            $this->message = $e->getMessage();
        }
    }

    #[LiveAction]
    public function refund(): void
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            $this->message = 'Vous devez être connecté.';
            return;
        }
        try {
            $this->bookingService->refund($this->booking);
            $this->message = 'Réservation remboursée.';
        } catch (\LogicException $e) {
            $this->message = $e->getMessage();
        }
    }
}
