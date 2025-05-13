<?php

namespace App\Controller\Api;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ArticleApiController extends AbstractController
{
    #[Route('/articles/search', name: 'api_articles_search', methods: ['GET'])]
    public function search(Request $request, ArticleRepository $articleRepository): JsonResponse
    {
        try {
            $query = $request->query->get('q', '');
            $category = $request->query->get('category', '');
            $page = $request->query->getInt('page', 1);
            $limit = 6;

            $articles = $articleRepository->searchWithFilters($query, $category, $page, $limit);
            $total = $articleRepository->countSearchResults($query, $category);

            return $this->json([
                'articles' => array_map(function($article) {
                    return [
                        'id' => $article->getId(),
                        'title' => $article->getTitle(),
                        'content' => substr($article->getContent(), 0, 150) . '...',
                        'category' => $article->getCategory(),
                        'author' => $article->getUser() ? $article->getUser()->getEmail() : 'Anonyme',
                        'date' => $article->getCreatedAt()->format('d/m/Y H:i'),
                        'commentsCount' => count($article->getComments()),
                    ];
                }, $articles),
                'pagination' => [
                    'current' => $page,
                    'total' => ceil($total / $limit),
                    'hasMore' => ($page * $limit) < $total
                ]
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Une erreur est survenue lors du chargement des articles',
                'details' => $e->getMessage()
            ], 500);
        }
    }
} 