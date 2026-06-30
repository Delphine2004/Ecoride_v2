<?php

namespace App\Service;

use App\Entity\Ride;
use App\Entity\Booking;
use App\Entity\User;
use App\Enum\BookingStatus;
use App\Enum\RideStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class RideService extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
        private Security $security
    ) {}

    public function book(Ride $ride): void
    {

        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new \LogicException('Merci de vous connecter');
        }

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

        $this->entityManagerInterface->flush();
    }

    public function cancel(Ride $ride): void
    {
        $ride->setStatus(RideStatus::CANCELLED);

        foreach ($ride->getRideBookings() as $booking) {
            $booking->setStatus(BookingStatus::CANCELLED);
        }

        $this->entityManagerInterface->flush();
    }

    public function start(Ride $ride): void
    {
        $ride->setStatus(RideStatus::RUNNING);

        foreach ($ride->getRideBookings() as $booking) {
            $booking->setStatus(BookingStatus::RUNNING);
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
        // Manque la logique

        $ride->setStatus(RideStatus::COMPLETED);


        $this->entityManagerInterface->flush();
    }
}
