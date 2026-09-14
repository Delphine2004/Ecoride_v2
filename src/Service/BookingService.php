<?php

namespace App\Service;

use App\Entity\Booking;
use App\Entity\User;
use App\Enum\BookingStatus;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;

class BookingService
{

    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
        private EmailService $emailService
    ) {}


    public function cancelAfterRideCancelation(Booking $booking, User $user): void
    {
        if ($booking->getStatus() !== BookingStatus::CANCELLED) {
            $booking->setStatus(BookingStatus::CANCELLED);

            $ride = $booking->getRide();

            $user->refundCredit($ride->getPrice());

            $this->emailService->sendCancelationRideToPassenger($user, $ride, $booking);
            $this->entityManagerInterface->flush();
        }
    }

    public function cancel(Booking $booking, User $user): void
    {
        $booking->setStatus(BookingStatus::CANCELLED);

        $ride = $booking->getRide();

        $ride->releaseSeat();
        $user->refundCredit($ride->getPrice());
        $this->emailService->sendCancelationBookingToPassenger($user, $ride, $booking);
        $this->emailService->sendCancelationBookingToDriver($user, $ride, $booking);
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
