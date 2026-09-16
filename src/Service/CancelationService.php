<?php

namespace App\Service;

use App\Entity\Ride;
use App\Entity\Booking;
use App\Entity\User;

use App\Enum\RideStatus;
use App\Enum\BookingStatus;

use App\Service\EmailService;

use Doctrine\ORM\EntityManagerInterface;

class CancelationService
{

    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
        private EmailService $emailService
    ) {}

    public function cancelBookingAfterRideCancelation(
        Booking $booking,
        User $user
    ): void {
        if ($booking->getStatus() !== BookingStatus::CANCELLED) {
            $booking->setStatus(BookingStatus::CANCELLED);

            $ride = $booking->getRide();

            $user->refundCredit($ride->getPrice());

            $this->emailService->sendCancelationRideToPassenger($user, $ride, $booking);
            $this->entityManagerInterface->flush();
        }
    }


    public function cancelBooking(
        Booking $booking,
        User $user
    ): void {
        $booking->setStatus(BookingStatus::CANCELLED);

        $ride = $booking->getRide();

        $ride->releaseSeat();
        $user->refundCredit($ride->getPrice());
        $this->emailService->sendCancelationBookingToPassenger($user, $ride, $booking);
        $this->emailService->sendCancelationBookingToDriver($user, $ride, $booking);
        $this->entityManagerInterface->flush();
    }


    public function cancelRide(
        Ride $ride,
        User $user
    ): void {

        $ride->setStatus(RideStatus::CANCELLED);

        foreach ($ride->getRideBookings() as $booking) {
            $this->cancelBookingAfterRideCancelation($booking, $booking->getPassenger());
        }

        $this->emailService->sendCancelationRideToDriver($user, $ride);

        $this->entityManagerInterface->flush();
    }
}
