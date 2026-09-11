<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Ride;

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


    public function sendConfirmationRegistration(User $user): void
    {
        $this->sendTemplate(
            to: $user->getEmail(),
            subject: 'Bienvenue !',
            template: 'emails/user_registration.html.twig',
            context: [
                'user' => $user,
            ],
        );
    }

    public function sendConfirmationEditStatus(User $user): void
    {
        $this->sendTemplate(
            to: $user->getEmail(),
            subject: 'Modification de statut',
            template: 'emails/user_become_driver.html.twig',
            context: [
                'user' => $user,
            ],
        );
    }

    public function sendConfirmationRide(User $user, Ride $ride): void
    {
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
}
