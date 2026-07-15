<?php

namespace App\Twig\Components;

use App\Entity\Booking;
use App\Entity\User;
use App\Service\BookingService;

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
        private BookingService $bookingService
    ) {}

    #[LiveProp]
    public Booking $booking;


    public function getUser(): ?User
    {
        return $this->security->getUser();
    }


    #[LiveAction]
    public function cancel(): void
    {
        $this->bookingService->cancel($this->booking, $this->getUser());
    }

    #[LiveAction]
    public function report(): void
    {
        $this->bookingService->report($this->booking);
    }

    #[LiveAction]
    public function finalize(): void
    {
        $this->bookingService->finalize($this->booking);
    }

    #[LiveAction]
    public function refund(): void
    {
        $this->bookingService->refund($this->booking);
    }
}
