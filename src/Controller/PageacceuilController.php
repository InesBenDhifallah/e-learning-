<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PageacceuilController extends AbstractController
{
    #[Route('/pageacceuil', name: 'app_pageacceuil')]
    public function index(): Response
    {
        return $this->render('pageacceuil/pageacceuil.html.twig', [
            'controller_name' => 'PageacceuilController',
        ]);
    }
}
