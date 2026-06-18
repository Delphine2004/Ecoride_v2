<?php

namespace App\Service;

use App\Entity\Ride;
use App\Enum\BookingStatus;
use App\Enum\RideStatus;
use Doctrine\ORM\EntityManagerInterface;

class RideService
{
    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
    ) {}

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

    /*
    // à faire

    public function book(): Ride {
         $ride->setStatus(RideStatus::CONFIRMED);
          $this->entityManagerInterface->flush();
    }
    */
}
