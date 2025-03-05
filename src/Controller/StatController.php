<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StatController extends AbstractController
{
    #[Route('/stat', name: 'app_stat')]
    public function index(): Response
    {
        return $this->render('stat/statistics.html.twig', [
            'controller_name' => 'StatController',
        ]);
    }
}
