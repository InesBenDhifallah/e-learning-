<?php
namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(string $email, string $subject, string $message): void
    {
        $email = (new Email())
            ->from('bendhifallahines@gmail.com')  
            ->to($email)  
            ->subject($subject)  
            ->text($message);  

        
        $this->mailer->send($email);
    }
}
