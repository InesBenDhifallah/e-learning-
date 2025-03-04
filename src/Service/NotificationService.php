<?php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class NotificationService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendPaymentNotification(string $email, string $subject, string $message): void
    {
        $email = (new Email())
            ->from('bendhifallahines@gmail.com')  // Ton adresse email
            ->to($email)  // Adresse du destinataire
            ->subject($subject)  // Sujet de l'email
            ->text($message);  // Contenu de l'email

        // Envoie l'email
        $this->mailer->send($email);
    }
}
