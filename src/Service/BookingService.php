<?php

namespace App\Service;

use App\Entity\Booking;
use App\Enum\BookingStatus;
use Doctrine\ORM\EntityManagerInterface;

class BookingService
{

    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
    ) {}


    public function cancel(Booking $booking): void
    {
        $booking->setStatus(BookingStatus::CANCELLED);
        $this->entityManagerInterface->flush();
    }


    public function book(Booking $booking): void
    {
        $booking->setStatus(BookingStatus::CONFIRMED);
        $this->entityManagerInterface->flush();
    }

    public function report(Booking $booking): void
    {
        $booking->setStatus(BookingStatus::REPORTED);
        $this->entityManagerInterface->flush();
    }

    public function finalize(Booking $booking): void
    {
        $booking->setStatus(BookingStatus::FINALIZED);
        $this->entityManagerInterface->flush();
    }

    public function refund(Booking $booking): void
    {
        $booking->setStatus(BookingStatus::REFUNDED);
        $this->entityManagerInterface->flush();
    }
}
