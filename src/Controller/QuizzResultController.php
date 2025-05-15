<?php

namespace App\Controller;

use App\Repository\QuizzResultRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use App\Repository\UserRepository;

final class QuizzResultController extends AbstractController
{
    #[Route('/quizz/result', name: 'app_quizz_result')]
    public function index(): Response
    {
        return $this->render('quizz_result/index.html.twig', [
            'controller_name' => 'QuizzResultController',
        ]);
    }
    

    #[Route('/quizz/average', name: 'quizz_average')]
    public function averageScore(UserRepository $userRepository, QuizzResultRepository $quizzResultRepository): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        if (!$user) {
            // Si l'utilisateur n'est pas connecté, retourner une erreur (par exemple, une réponse 401 Unauthorized)
            return $this->json(['error' => 'User not authenticated'], 401);
        }

        // Extraire l'ID de l'utilisateur connecté à partir de son email
        $userId = $this->getUserIdByEmail($user->getUserIdentifier(), $userRepository);

        if (!$userId) {
            return $this->json(['error' => 'User not found'], 404);
        }

        // Obtenir la moyenne des scores pour l'utilisateur avec l'ID récupéré
        $averages = $quizzResultRepository->getAverageScoreByMatiere($userId);

        return $this->render('stat/statistics.html.twig', [
            'averages' => $averages,
        ]);
    }

    // Méthode pour obtenir l'ID de l'utilisateur à partir de son email
    private function getUserIdByEmail(string $email, UserRepository $userRepository): ?int
    {
        // Find the user by email
        $user = $userRepository->findOneBy(['email' => $email]);

        // Check if the user was found
        if (!$user) {
            return null; // Return null if the user doesn't exist
        }

        // Return the user ID
        return $user->getId();
    }
}


