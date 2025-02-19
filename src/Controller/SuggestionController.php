<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SuggestionController extends AbstractController
{
    #[Route('/suggestion', name: 'app_suggestion')]
    public function index(): Response
    {
        return $this->render('suggestion/index.html.twig', [
            'controller_name' => 'SuggestionController',
        ]);
    }
}
