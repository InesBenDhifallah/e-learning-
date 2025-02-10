<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PayController extends AbstractController
{
    #[Route('/pay/{prix}', name: 'pay')]
    public function index($prix): Response
    {
        return $this->render('pay/paiement.html.twig', [
            'prix' => $prix, 
        ]);
    }
}
