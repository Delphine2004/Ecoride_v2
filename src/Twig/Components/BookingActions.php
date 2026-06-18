<?php

namespace App\Twig\Components;

use App\Entity\Booking;
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
    public Booking $booking;

    public function __construct(
        private BookingService $bookingService
    ) {}

    #[LiveAction]
    public function cancel(): void
    {
        $this->bookingService->cancel($this->booking);
    }

    #[LiveAction]
    public function book(): void
    {
        $this->bookingService->book($this->booking);
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
