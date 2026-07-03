<?php

namespace App\Twig\Components;

use App\Entity\Booking;
use App\Entity\User;
use App\Service\BookingService;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

#[AsLiveComponent]
final class BookingActions
{
    use DefaultActionTrait;

    #[LiveProp]
    public ?string $message = null;

    #[LiveProp]
    public Booking $booking;

    #[LiveProp]
    public User $user;

    public function __construct(
        private BookingService $bookingService
    ) {}

    #[LiveAction]
    public function cancel(): void
    {
        $this->bookingService->cancel($this->booking, $this->user);
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
