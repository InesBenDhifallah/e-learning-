<?php

namespace App\Controller\Api;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api', name: 'api_')]
class ArticleCommentApiController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator
    ) {}

    // Articles List API
    #[Route('/articles', name: 'articles_list', methods: ['GET'])]
    public function listArticles(ArticleRepository $articleRepository): JsonResponse
    {
        try {
            $articles = $articleRepository->findBy([], ['createdAt' => 'DESC']);
            return $this->json([
                'status' => 'success',
                'articles' => $articles
            ], Response::HTTP_OK, [], ['groups' => ['article:read']]);
        } catch (\Exception $e) {
            return $this->json(['status' => 'error', 'message' => 'Erreur lors de la récupération des articles'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Create Article API
    #[Route('/articles', name: 'article_create', methods: ['POST'])]
    #[IsGranted('ROLE_TEACHER')]
    public function createArticle(Request $request): JsonResponse
    {
        try {
            $article = new Article();
            $article->setUser($this->getUser());
            $article->setCreatedAt(new \DateTimeImmutable());
            
            $data = json_decode($request->getContent(), true);
            $article->setTitle($data['title']);
            $article->setContent($data['content']);

            $errors = $this->validator->validate($article);
            if (count($errors) > 0) {
                return $this->json(['status' => 'error', 'errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->persist($article);
            $this->entityManager->flush();

            return $this->json([
                'status' => 'success',
                'article' => $article
            ], Response::HTTP_CREATED, [], ['groups' => 'article:read']);
        } catch (\Exception $e) {
            return $this->json(['status' => 'error', 'message' => 'Erreur lors de la création de l\'article'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Get Article with Comments API
    #[Route('/articles/{id}', name: 'article_show', methods: ['GET'])]
    public function showArticle(Article $article): JsonResponse
    {
        try {
            return $this->json([
                'status' => 'success',
                'article' => $article,
                'comments' => $article->getComments()
            ], Response::HTTP_OK, [], ['groups' => ['article:read', 'comment:read', 'user:read']]);
        } catch (\Exception $e) {
            return $this->json(['status' => 'error', 'message' => 'Erreur lors de la récupération de l\'article'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Update Article API
    #[Route('/articles/{id}', name: 'article_update', methods: ['PUT'])]
    public function updateArticle(Article $article, Request $request): JsonResponse
    {
        try {
            if ($article->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
                return $this->json(['status' => 'error', 'message' => 'Non autorisé'], Response::HTTP_FORBIDDEN);
            }

            $data = json_decode($request->getContent(), true);
            $article->setTitle($data['title'] ?? $article->getTitle());
            $article->setContent($data['content'] ?? $article->getContent());

            $this->entityManager->flush();

            return $this->json([
                'status' => 'success',
                'article' => $article
            ], Response::HTTP_OK, [], ['groups' => 'article:read']);
        } catch (\Exception $e) {
            return $this->json(['status' => 'error', 'message' => 'Erreur lors de la mise à jour'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Delete Article API
    #[Route('/articles/{id}', name: 'article_delete', methods: ['DELETE'])]
    public function deleteArticle(Article $article): JsonResponse
    {
        try {
            if ($article->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
                return $this->json(['status' => 'error', 'message' => 'Non autorisé'], Response::HTTP_FORBIDDEN);
            }

            $this->entityManager->remove($article);
            $this->entityManager->flush();

            return $this->json(['status' => 'success', 'message' => 'Article supprimé'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['status' => 'error', 'message' => 'Erreur lors de la suppression'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Add Comment API
    #[Route('/articles/{id}/comments', name: 'comment_add', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function addComment(Article $article, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            $comment = new Comment();
            $comment->setContent($data['content']);
            $comment->setArticle($article);
            $comment->setUser($this->getUser());
            $comment->setCreatedAt(new \DateTimeImmutable());

            $errors = $this->validator->validate($comment);
            if (count($errors) > 0) {
                return $this->json(['status' => 'error', 'errors' => (string) $errors], Response::HTTP_BAD_REQUEST);
            }

            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            return $this->json([
                'status' => 'success',
                'comment' => $comment
            ], Response::HTTP_CREATED, [], ['groups' => 'comment:read']);
        } catch (\Exception $e) {
            return $this->json(['status' => 'error', 'message' => 'Erreur lors de l\'ajout du commentaire'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Update Comment API
    #[Route('/comments/{id}', name: 'comment_update', methods: ['PUT'])]
    public function updateComment(Comment $comment, Request $request): JsonResponse
    {
        try {
            if ($comment->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
                return $this->json(['status' => 'error', 'message' => 'Non autorisé'], Response::HTTP_FORBIDDEN);
            }

            $data = json_decode($request->getContent(), true);
            $comment->setContent($data['content']);

            $this->entityManager->flush();

            return $this->json([
                'status' => 'success',
                'comment' => $comment
            ], Response::HTTP_OK, [], ['groups' => 'comment:read']);
        } catch (\Exception $e) {
            return $this->json(['status' => 'error', 'message' => 'Erreur lors de la mise à jour du commentaire'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Delete Comment API
    #[Route('/comments/{id}', name: 'comment_delete', methods: ['DELETE'])]
    public function deleteComment(Comment $comment): JsonResponse
    {
        try {
            if ($comment->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
                return $this->json(['status' => 'error', 'message' => 'Non autorisé'], Response::HTTP_FORBIDDEN);
            }

            $this->entityManager->remove($comment);
            $this->entityManager->flush();

            return $this->json(['status' => 'success', 'message' => 'Commentaire supprimé'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['status' => 'error', 'message' => 'Erreur lors de la suppression du commentaire'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Search Articles API
    #[Route('/articles/search', name: 'articles_search', methods: ['GET'])]
    public function searchArticles(Request $request, ArticleRepository $articleRepository): JsonResponse
    {
        try {
            $query = $request->query->get('q');
            $page = $request->query->getInt('page', 1);
            $limit = $request->query->getInt('limit', 10);

            $articles = $articleRepository->searchByTerm($query, $page, $limit);
            
            return $this->json([
                'status' => 'success',
                'articles' => $articles,
                'page' => $page,
                'limit' => $limit
            ], Response::HTTP_OK, [], ['groups' => 'article:read']);
        } catch (\Exception $e) {
            return $this->json(['status' => 'error', 'message' => 'Erreur lors de la recherche'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Recent Activity API
    #[Route('/recent-activity', name: 'recent_activity', methods: ['GET'])]
    public function getRecentActivity(ArticleRepository $articleRepository, CommentRepository $commentRepository): JsonResponse
    {
        try {
            $recentArticles = $articleRepository->findRecent(5);
            $recentComments = $commentRepository->findRecent(5);

            return $this->json([
                'status' => 'success',
                'recent_articles' => $recentArticles,
                'recent_comments' => $recentComments
            ], Response::HTTP_OK, [], ['groups' => ['article:read', 'comment:read', 'user:read']]);
        } catch (\Exception $e) {
            return $this->json(['status' => 'error', 'message' => 'Erreur lors de la récupération de l\'activité récente'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
} 