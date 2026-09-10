<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;


final class EmailService
{
    public function __construct(
        private MailerInterface $mailer,
    ) {}

    public function send(
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
}
