<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AddparentController extends AbstractController
{
    #[Route('/addparent', name: 'app_addparent')]
    public function index(): Response
    {
        return $this->render('addparent/addparent.html.twig', [
            'controller_name' => 'AddparentController',
        ]);
    }
}
