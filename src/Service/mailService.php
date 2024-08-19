<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class mailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    // Calling the function to send mail 
    public function sendEmail(string $to, string $subject, string $body): void
    {
        $email = (new Email())
            ->from($_ENV['FROM_EMAIL']) 
            ->to($to)
            ->subject($subject)
            ->text($body);

        $this->mailer->send($email);
    }
}