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
        $limit = 5;
        $page = $request->query->getInt('page', 1);
        $offset = ($page - 1) * $limit;
        $query = $entityManager->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();
        $users = $query->getResult();
        $totalCount = $entityManager->createQueryBuilder()
            ->select('count(u.id)')
            ->from(User::class, 'u')
            ->getQuery()
            ->getSingleScalarResult();
        $totalPages = ceil($totalCount / $limit);
        return $this->render('listusers/index.html.twig', [
            'users' => $users,
            'page' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
