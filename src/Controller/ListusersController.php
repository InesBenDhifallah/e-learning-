<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        // Render the initial page
        return $this->render('listusers/index.html.twig');
    }

    #[Route('/listusers/filter', name: 'app_listusers_filter', methods: ['GET'])]
    public function filter(
        EntityManagerInterface $entityManager,
        Request $request
    ): JsonResponse {
        $limit = 5; // Limit to 5 results per page
        $page = $request->query->getInt('page', 1);
        $offset = ($page - 1) * $limit;

        // Get filter parameters from the request
        $nameFilter = $request->query->get('name');
        $roleFilter = $request->query->get('role');

        // Build the query
        $queryBuilder = $entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        // Apply name filter if provided
        if ($nameFilter) {
            $queryBuilder->andWhere('u.nom LIKE :name')
                ->setParameter('name', '%' . $nameFilter . '%');
        }

        // Apply role filter if provided
        if ($roleFilter) {
            $queryBuilder->andWhere('u.roles LIKE :role')
                ->setParameter('role', '%' . $roleFilter . '%');
        }

        $users = $queryBuilder->getQuery()->getResult();

        // Count total users for pagination
        $countQueryBuilder = $entityManager->createQueryBuilder()
            ->select('count(u.id)')
            ->from(User::class, 'u');

        // Apply the same filters to the count query
        if ($nameFilter) {
            $countQueryBuilder->andWhere('u.nom LIKE :name')
                ->setParameter('name', '%' . $nameFilter . '%');
        }

        if ($roleFilter) {
            $countQueryBuilder->andWhere('u.roles LIKE :role')
                ->setParameter('role', '%' . $roleFilter . '%');
        }

        $totalCount = $countQueryBuilder->getQuery()->getSingleScalarResult();
        $totalPages = ceil($totalCount / $limit);

        // Prepare the data to return as JSON
        $data = [
            'users' => array_map(function (User $user) {
                return [
                    'id' => $user->getId(),
                    'nom' => $user->getNom(),
                    'email' => $user->getEmail(),
                    'roles' => $user->getRoles(),
                    'phonenumber' => $user->getPhonenumber(),
                    'adress' => $user->getAdress(),
                    'isActive' => $user->isActive(),
                ];
            }, $users),
            'page' => $page,
            'totalPages' => $totalPages,
        ];

        return new JsonResponse($data);
    }

    #[Route('/listusers/toggle-status/{id}', name: 'app_listusers_toggle_status', methods: ['POST'])]
    public function toggleStatus(
        EntityManagerInterface $entityManager,
        Request $request,
        User $user
    ): JsonResponse {
        // Toggle the isActive status
        $user->setIsActive(!$user->isActive());
        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'isActive' => $user->isActive(),
        ]);
    }
}