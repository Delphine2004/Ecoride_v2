<?php

namespace App\Service;

use App\Entity\Ride;
use App\Entity\Booking;
use App\Entity\User;

use App\Enum\BookingStatus;

use App\Service\EmailService;

use Doctrine\ORM\EntityManagerInterface;

class CreationService
{

    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
        private EmailService $emailService
    ) {}

    public function bookRide(
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
}
