<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ListusersController extends AbstractController
{
    #[Route('/listusers', name: 'app_listusers')]
    public function index(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        // Define how many items per page
        $limit = 5;
        
        // Get the current page (default is 1 if not set)
        $page = $request->query->getInt('page', 1);
        
        // Calculate the offset for the query (for pagination)
        $offset = ($page - 1) * $limit;
        
        // Fetch the users with pagination
        $query = $entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();
        
        // Execute query to get users for this page
        $users = $query->getResult();
        
        // Get the total number of users for pagination
        $totalCount = $entityManager->createQueryBuilder()
            ->select('count(u.id)')
            ->from(User::class, 'u')
            ->getQuery()
            ->getSingleScalarResult();
        
        // Calculate the total number of pages
        $totalPages = ceil($totalCount / $limit);

        // Return the result to the Twig template
        return $this->render('listusers/index.html.twig', [
            'users' => $users,
            'page' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
