<?php

namespace App\Controller\Api;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/stats')]
class StatsApiController extends AbstractController
{
    #[Route('', name: 'api_stats', methods: ['GET'])]
    public function getStats(ArticleRepository $articleRepository): JsonResponse
    {
        $articles = $articleRepository->findAll();
        
        // Statistiques générales
        $stats = [
            'total_articles' => count($articles),
            'total_comments' => 0,
            'categories' => [],
            'comments_per_article' => [],
            'articles_per_month' => array_fill(0, 12, 0)
        ];

        // Calcul des statistiques
        foreach ($articles as $article) {
            // Comptage des commentaires
            $stats['total_comments'] += count($article->getComments());
            
            // Stats par catégorie
            if ($article->getCategory()) {
                $category = $article->getCategory();
                if (!isset($stats['categories'][$category])) {
                    $stats['categories'][$category] = 0;
                }
                $stats['categories'][$category]++;
            }

            // Commentaires par article
            $stats['comments_per_article'][] = [
                'title' => $article->getTitle(),
                'comments' => count($article->getComments())
            ];

            // Articles par mois
            $month = $article->getCreatedAt()->format('n') - 1;
            $stats['articles_per_month'][$month]++;
        }

        return $this->json($stats);
    }
} 