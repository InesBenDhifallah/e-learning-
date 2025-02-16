<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ListusersController extends AbstractController
{
    #[Route('/listusers', name: 'app_listusers')]
    public function index(
        EntityManagerInterface $entityManager,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        // Fetch all users from the database
        $query = $entityManager->createQueryBuilder()
            ->select('u.id, u.email, u.nom, u.phonenumber, u.roles, u.work, u.adress, u.pref, u.isActive')
            ->from(User::class, 'u')
            ->getQuery();

        // Paginate the query results
        $users = $paginator->paginate(
            $query, // Query to paginate
            $request->query->getInt('page', 1), // Current page number (default: 1)
            10 // Number of items per page
        );

        // Render the Twig template and pass the paginated users
        return $this->render('listusers/index.html.twig', [
            'users' => $users,
        ]);
    }
}