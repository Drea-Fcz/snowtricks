<?php

namespace App\Service;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmail(string $to, string $object, string $template): void
    {
        $email = (new Email())
            ->from('fcz.audrey@gmail.com')
            ->to($to)
            ->subject($object)
            ->html($template);

        $this->mailer->send($email);
    }
}
