<?php

namespace App\Service;

use App\Entity\Ride;
use App\Entity\Booking;
use App\Entity\User;
use App\Enum\BookingStatus;
use App\Enum\RideStatus;
use App\Service\BookingService;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;


class RideService
{
    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
        private BookingService $bookingService,
        private EmailService $emailService
    ) {}

    public function book(
        Ride $ride,
        User $user
    ): void {

        $ridePrice = $ride->getPrice();

        if ($user->getCredit() < $ridePrice) {
            throw new \LogicException('Crédits insuffisants');
        }

        $ride->reserveSeat();

        $user->spendCredit($ridePrice);

        $booking = new Booking();
        $booking->setStatus(BookingStatus::CONFIRMED);
        $booking->setRide($ride);
        $booking->setPassenger($user);

        $this->entityManagerInterface->persist($booking);

        $this->emailService->sendConfirmationBookingToDriver($user, $ride);
        $this->emailService->sendConfirmationBookingToPassenger($user, $ride, $booking);

        $this->entityManagerInterface->flush();
    }


    public function cancel(
        Ride $ride,
        User $user
    ): void {

        $ride->setStatus(RideStatus::CANCELLED);

        foreach ($ride->getRideBookings() as $booking) {
            $this->bookingService->cancel($booking, $booking->getPassenger());
        }

        $this->emailService->sendCancelationRideToDriver($user, $ride);

        $this->entityManagerInterface->flush();
    }

    public function start(Ride $ride): void
    {

        $ride->setStatus(RideStatus::RUNNING);

        foreach ($ride->getRideBookings() as $booking) {
            if ($booking->getStatus() !== BookingStatus::CANCELLED) {
                $booking->setStatus(BookingStatus::RUNNING);
            }
        }

        $this->entityManagerInterface->flush();
    }

    public function stop(Ride $ride): void
    {
        $ride->setStatus(RideStatus::PENDING);

        foreach ($ride->getRideBookings() as $booking) {
            $booking->setStatus(BookingStatus::PENDING);
        }

        $this->entityManagerInterface->flush();
    }

    public function finalize(Ride $ride): void
    {
        // A FAIRE - Manque la logique - vérification que toutes les réservations attachées sont finalisées

        $ride->setStatus(RideStatus::COMPLETED);


        $this->entityManagerInterface->flush();
    }
}
