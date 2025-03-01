<?php
namespace App\Service;

use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\Recipient;

class NotificationService
{
    private $notifier;

    public function __construct(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }

    public function sendPaymentNotification(string $userPhoneNumber): void
    {
        // Créer une notification
        $notification = new Notification(
            'Votre paiement sur la plateforme Alpha Education a été effectué avec succès.',
            ['sms']  // Choisir le canal (ici SMS)
        );

        // Créer un destinataire (par exemple, un numéro de téléphone)
        $recipient = new Recipient($userPhoneNumber);

        // Envoyer la notification
        $this->notifier->send($notification, $recipient);
    }
}
