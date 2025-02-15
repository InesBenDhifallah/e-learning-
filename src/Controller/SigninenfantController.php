<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SigninenfantController extends AbstractController
{
    #[Route('/signinenfant', name: 'app_signinenfant')]
    public function index(): Response
    {
        return $this->render('signinenfant/signinenfant.html.twig', [
            'controller_name' => 'SigninenfantController',
        ]);
    }
}
