<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Ride;
use App\Entity\Booking;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;


final class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
    ) {}

    private function sendTemplate(
        string $to,
        string $subject,
        string $template,
        array $context = [],
    ): void {
        $email = (new TemplatedEmail())
            ->from('no-reply@ecoride.com')
            ->to($to)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context($context);

        $this->mailer->send($email);
    }


    public function sendConfirmationRegistration(
        User $user
    ): void {
        $this->sendTemplate(
            to: $user->getEmail(),
            subject: 'Bienvenue !',
            template: 'emails/user_registration.html.twig',
            context: [
                'user' => $user,
            ],
        );
    }

    public function sendConfirmationEditStatus(
        User $user
    ): void {
        $this->sendTemplate(
            to: $user->getEmail(),
            subject: 'Modification de statut',
            template: 'emails/user_become_driver.html.twig',
            context: [
                'user' => $user,
            ],
        );
    }

    public function sendConfirmationRide(
        User $user,
        Ride $ride
    ): void {
        $this->sendTemplate(
            to: $user->getEmail(),
            subject: 'Confirmation trajet',
            template: 'emails/ride_confirmation.html.twig',
            context: [
                'user' => $user,
                'ride' => $ride
            ],
        );
    }

    public function sendConfirmationBookingToPassenger(
        User $user,
        Ride $ride,
        Booking $booking
    ): void {
        $this->sendTemplate(
            to: $user->getEmail(),
            subject: 'Confirmation réservation',
            template: 'emails/booking_confirmation_passenger.html.twig',
            context: [
                'user' => $user,
                'ride' => $ride,
                'booking' => $booking
            ],
        );
    }

    public function sendConfirmationBookingToDriver(
        User $user,
        Ride $ride
    ): void {
        $this->sendTemplate(
            to: $user->getEmail(),
            subject: 'Actualisation trajet - Un nouveau passager',
            template: 'emails/booking_confirmation_driver.html.twig',
            context: [
                'user' => $user,
                'ride' => $ride
            ],
        );
    }

    public function sendCancelationRideToPassenger(
        User $user,
        Ride $ride,
        Booking $booking
    ): void {
        $this->sendTemplate(
            to: $user->getEmail(),
            subject: 'Annulation réservation',
            template: 'emails/ride_cancellation_passenger.html.twig',
            context: [
                'user' => $user,
                'ride' => $ride,
                'booking' => $booking
            ],
        );
    }

    public function sendCancelationRideToDriver(
        User $user,
        Ride $ride
    ): void {
        $this->sendTemplate(
            to: $user->getEmail(),
            subject: 'Annulation trajet',
            template: 'emails/ride_cancellation_driver.html.twig',
            context: [
                'user' => $user,
                'ride' => $ride
            ],
        );
    }

    public function sendCancelationBookingToPassenger(
        User $user,
        Ride $ride,
        Booking $booking
    ): void {
        $this->sendTemplate(
            to: $user->getEmail(),
            subject: 'Annulation réservation',
            template: 'emails/booking_cancellation_passenger.html.twig',
            context: [
                'user' => $user,
                'ride' => $ride,
                'booking' => $booking
            ],
        );
    }

    public function sendCancelationBookingToDriver(
        User $user,
        Ride $ride,
        Booking $booking
    ): void {
        $this->sendTemplate(
            to: $user->getEmail(),
            subject: 'Actualisation trajet - Annulation réservation',
            template: 'emails/booking_cancellation_driver.html.twig',
            context: [
                'user' => $user,
                'ride' => $ride,
                'booking' => $booking
            ],
        );
    }

    public function sendConfirmationRideStarted(
        User $user,
        Ride $ride
    ): void {
        $this->sendTemplate(
            to: $user->getEmail(),
            subject: 'Le trajet a démarré',
            template: 'emails/ride_start.html.twig',
            context: [
                'user' => $user,
                'ride' => $ride
            ],
        );
    }

    public function sendConfirmationRideStoppedDriver(
        User $user,
        Ride $ride
    ): void {
        $this->sendTemplate(
            to: $user->getEmail(),
            subject: 'Vous êtes arrivé(e)s',
            template: 'emails/ride_stop_driver.html.twig',
            context: [
                'user' => $user,
                'ride' => $ride
            ],
        );
    }

    public function sendConfirmationRideStoppedPassenger(
        User $user,
        Ride $ride
    ): void {
        $this->sendTemplate(
            to: $user->getEmail(),
            subject: 'Vous êtes arrivé(e)s',
            template: 'emails/ride_stop_passenger.html.twig',
            context: [
                'user' => $user,
                'ride' => $ride
            ],
        );
    }
}
