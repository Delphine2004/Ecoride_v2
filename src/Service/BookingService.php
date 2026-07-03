<?php

namespace App\Service;

use App\Entity\Booking;
use App\Entity\User;
use App\Enum\BookingStatus;
use Doctrine\ORM\EntityManagerInterface;

class BookingService
{

    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
    ) {}


    public function cancel(Booking $booking, User $user): void
    {
        $booking->setStatus(BookingStatus::CANCELLED);

        $ride = $booking->getRide();

        $ride->releaseSeat();
        $user->refundCredit($ride->getPrice());

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
        // A FAIRE : 
        $this->entityManagerInterface->flush();
    }

    public function refund(Booking $booking): void
    {
        $booking->setStatus(BookingStatus::REFUNDED);
        // A FAIRE : 
        $this->entityManagerInterface->flush();
    }
}
