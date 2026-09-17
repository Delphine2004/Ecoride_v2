<?php

namespace App\Service;

use App\Entity\Ride;
use App\Entity\Booking;

use App\Enum\BookingStatus;
use App\Enum\RideStatus;

use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;


class StateService
{
    public function __construct(
        private EntityManagerInterface $entityManagerInterface,
        private EmailService $emailService
    ) {}

    public function startRide(
        Ride $ride
    ): void {

        $ride->setStatus(RideStatus::RUNNING);

        foreach ($ride->getRideBookings() as $booking) {
            if ($booking->getStatus() !== BookingStatus::CANCELLED) {
                $booking->setStatus(BookingStatus::RUNNING);
                $this->emailService->sendConfirmationRideStarted($booking->getPassenger(), $ride);
            }
        }

        $this->emailService->sendConfirmationRideStarted($ride->getDriver(), $ride);

        $this->entityManagerInterface->flush();
    }


    public function stopRide(
        Ride $ride
    ): void {
        $ride->setStatus(RideStatus::PENDING);

        foreach ($ride->getRideBookings() as $booking) {
            if ($booking->getStatus() !== BookingStatus::CANCELLED) {
                $booking->setStatus(BookingStatus::PENDING);
                $this->emailService->sendConfirmationRideStoppedPassenger($booking->getPassenger(), $ride);
            }
        }

        $this->emailService->sendConfirmationRideStoppedDriver($ride->getDriver(), $ride);

        $this->entityManagerInterface->flush();
    }


    public function finalizeRide(
        Ride $ride
    ): void {
        // vérification que le trajet n'est pas déjà finalisé
        if ($ride->getStatus() === RideStatus::COMPLETED) {
            return;
        }

        // vérification que le trajet a bien un conducteur
        $driver = $ride->getDriver();
        if (!$driver) {
            throw new \LogicException("Impossible de finaliser un trajet sans chauffeur.");
        }

        // Initialisation des variables
        $totalCreditForDriver = 0;

        // Vérification des statuts ou de la limite de temps pour finaliser le trajet sur chaque réservation
        foreach ($ride->getRideBookings() as $booking) {
            // Vérifer que les réservations sont bien finalisées ou remboursées avant
            if ($booking->getStatus() !== BookingStatus::FINALIZED && $booking->getStatus() !== BookingStatus::REFUNDED && $booking->getStatus() !== BookingStatus::CANCELLED) {
                return;
            }
            // obtenir le total à créditer au chauffeur pour les réservations qui ne sont pas remboursées
            if ($booking->getStatus() === BookingStatus::FINALIZED) {
                $totalCreditForDriver += $ride->getPrice();
            }
        }

        $driver->setCredit($driver->getCredit() + $totalCreditForDriver);

        $ride->setStatus(RideStatus::COMPLETED);

        $this->emailService->sendRideFinalized($driver, $ride);

        $this->entityManagerInterface->flush();
    }


    public function reportBooking(
        Booking $booking
    ): void {

        $ride = $booking->getRide();
        $driver = $ride->getDriver();
        $passenger = $booking->getPassenger();

        $booking->setStatus(BookingStatus::REPORTED);
        $this->emailService->sendBookingReportedDriver($driver, $ride);
        $this->emailService->sendBookingReportedPassenger($passenger, $ride);

        $this->entityManagerInterface->flush();
    }


    public function finalizeBooking(
        Booking $booking
    ): void {

        $ride = $booking->getRide();
        $passenger = $booking->getPassenger();

        $booking->setStatus(BookingStatus::FINALIZED);
        $this->emailService->sendBookingFinalized($passenger, $ride);

        $this->finalizeRide($booking->getRide());

        $this->entityManagerInterface->flush();
    }


    public function refundBooking(
        Booking $booking
    ): void {

        $ride = $booking->getRide();
        $passenger = $booking->getPassenger();
        $price = $ride->getPrice();

        $booking->setStatus(BookingStatus::REFUNDED);
        $passenger->refundCredit($price);

        $this->emailService->sendBookingRefunded($passenger, $ride);


        $this->finalizeRide($booking->getRide());
        $this->entityManagerInterface->flush();
    }
}
