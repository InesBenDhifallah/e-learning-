<?php
namespace App\Service;

use App\Entity\Paiement;
use App\Entity\User;
use App\Entity\Abonnement;
use App\Repository\PaiementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Webhook;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class StripeService
{
    private string $secretKey;
    private string $webhookSecret;

    public function __construct(string $secretKey, string $webhookSecret)
    {
        $this->secretKey = $secretKey;
        $this->webhookSecret = $webhookSecret;
        \Stripe\Stripe::setApiKey($this->secretKey);
    }

    public function createCheckoutSession(Abonnement $abonnement, User $user): Session
    {
        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [


                    'currency' => 'tnd',


                    'product_data' => ['name' => 'Abonnement e-learning'],
                    'unit_amount' => $abonnement->getPrix() * 100, // Stripe utilise les centimes
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'http://127.0.0.1:8000/paiement/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://127.0.0.1:8000/payment/cancel',
            'metadata' => [
                'abonnement_id' => $abonnement->getId(),
                'user_id' => $user->getId(),
            ],
        ]);
    }

  


}
