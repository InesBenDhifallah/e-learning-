<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\UserRepository;
use App\Form\ArticleType;
use App\Service\TranslationService;
use App\Service\BadWordsFilter;

#[Route('/Auser')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class AUserController extends AbstractController
{
    private $entityManager;
    private $translationService;
    private $badWordsFilter;

    public function __construct(
        EntityManagerInterface $entityManager, 
        TranslationService $translationService,
        BadWordsFilter $badWordsFilter
    ) {
        $this->entityManager = $entityManager;
        $this->translationService = $translationService;
        $this->badWordsFilter = $badWordsFilter;
    }

    #[Route('/articles', name: 'user_articles')]
    public function articles(Request $request, ArticleRepository $articleRepository): Response
    {
        // Vérifier si l'utilisateur a l'un des rôles requis
        if (!$this->isGranted('ROLE_Parent') && !$this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        $page = $request->query->getInt('page', 1);
        $limit = 6;
        $category = $request->query->get('category');
        $search = $request->query->get('search');

        if ($search || $category) {
            $articles = $articleRepository->searchWithFilters($search, $category, $page, $limit);
            $total = $articleRepository->countSearchResults($search, $category);
        } else {
            $articles = $articleRepository->findPaginatedArticlesWithUsers($page, $limit);
            $total = $articleRepository->count([]);
        }

        return $this->render('Auser/articles.html.twig', [
            'articles' => $articles,
            'currentPage' => $page,
            'totalPages' => ceil($total / $limit),
            'currentCategory' => $category,
            'currentSearch' => $search
        ]);
    }

    #[Route('/article/{id}', name: 'user_article_show', methods: ['GET', 'POST'])]
    public function showArticle(
        Article $article, 
        Request $request, 
        EntityManagerInterface $em,
        ArticleRepository $articleRepository
    ): Response {
        if (!$this->isGranted('ROLE_Parent') && !$this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        // Charger l'article avec ses relations
        $article = $articleRepository->findArticleWithUserAndComments($article->getId());
        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        // Préparez les traductions si nécessaire
        $translations = [
            'original' => $article->getContent(),
            'english' => null,
            'arabic' => null
        ];

        // Créer un nouveau commentaire
        $comment = new Comment();
        $comment->setArticle($article);
        $comment->setUser($this->getUser());
        $comment->setCreatedAt(new \DateTimeImmutable());
        
        // Créer le formulaire
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $content = $comment->getContent();
            
            // Vérifier les bad words
            if ($this->badWordsFilter->hasBadWords($content)) {
                $badWords = $this->badWordsFilter->getBadWordsFound($content);
                $this->addFlash(
                    'error',
                    'Votre commentaire contient des mots inappropriés. Veuillez rester respectueux.'
                );
                return $this->redirectToRoute('user_article_show', ['id' => $article->getId()]);
            }

            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', 'Commentaire ajouté avec succès.');
            return $this->redirectToRoute('user_article_show', ['id' => $article->getId()]);
        }

        return $this->render('Auser/article_show.html.twig', [
            'article' => $article,
            'form' => $commentForm,
            'translations' => $translations
        ]);
    }

    #[Route('/comment/{id}/edit', name: 'user_comment_edit', methods: ['GET', 'POST'])]
    public function editComment(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser() || ($this->getUser() !== $comment->getUser() && !$this->isGranted('ROLE_ADMIN'))) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier ce commentaire.');
        }

        if ($request->isMethod('POST')) {
            $content = $request->request->get('content');
            
            // Vérifier les bad words
            if ($this->badWordsFilter->hasBadWords($content)) {
                $this->addFlash(
                    'error',
                    'Votre commentaire contient des mots inappropriés. Veuillez rester respectueux.'
                );
                return $this->render('article/edit_comment.html.twig', [
                    'comment' => $comment
                ]);
            }

            if ($content) {
                $comment->setContent($content);
                $entityManager->flush();
                
                $this->addFlash('success', 'Commentaire modifié avec succès.');
                return $this->redirectToRoute('article_show', ['id' => $comment->getArticle()->getId()]);
            }
        }

        return $this->render('article/edit_comment.html.twig', [
            'comment' => $comment
        ]);
    }

    #[Route('/comment/{id}/delete', name: 'user_comment_delete', methods: ['POST'])]
    public function deleteComment(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        // Vérifier si l'utilisateur a l'un des rôles requis
        if (!$this->isGranted('ROLE_Parent') && !$this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        // Vérifier si l'utilisateur est l'auteur du commentaire (sauf pour les enseignants)
        if (!$this->isGranted('ROLE_TEACHER') && $comment->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer ce commentaire.');
        }

        // Vérifier le token CSRF
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $em->remove($comment);
            $em->flush();
            $this->addFlash('success', 'Commentaire supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide, suppression annulée.');
        }

        // Rediriger vers l'article associé au commentaire
        return $this->redirectToRoute('user_article_show', ['id' => $comment->getArticle()->getId()]);
    }

    #[Route('/articles/search', name: 'user_articles_search', methods: ['GET'])]
    public function search(Request $request, ArticleRepository $articleRepository): Response
    {
        // Vérifier si l'utilisateur a l'un des rôles requis
        if (!$this->isGranted('ROLE_Parent') && !$this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        $query = $request->query->get('q');
        $articles = $articleRepository->searchByTitle($query);

        return $this->render('Auser/articles_search.html.twig', [
            'articles' => $articles,
            'query' => $query
        ]);
    }

    #[Route('/api/users/search', name: 'users_api_search', methods: ['GET'])]
    public function apiSearchUsers(Request $request, UserRepository $userRepository): JsonResponse
    {
        $query = $request->query->get('q', '');
        $role = $request->query->get('role', '');
        $page = $request->query->getInt('page', 1);
        $limit = 10; // Nombre d'utilisateurs par page
        
        $users = $userRepository->searchWithFilters($query, $role, $page, $limit);
        $total = $userRepository->countSearchResults($query, $role);
        $totalPages = ceil($total / $limit);
        
        $results = [
            'users' => array_map(function($user) {
                return [
                    'id' => $user->getId(),
                    'email' => $user->getEmail(),
                    'roles' => $user->getRoles(),
                    'isActive' => $user->isIsActive(),
                    'createdAt' => $user->getCreatedAt() ? $user->getCreatedAt()->format('d/m/Y') : null,
                ];
            }, $users),
            'pagination' => [
                'current' => $page,
                'total' => $totalPages,
                'hasMore' => $page < $totalPages
            ]
        ];

        return $this->json($results);
    }

    #[Route('/api/articles/search', name: 'user_articles_api_search', methods: ['GET'])]
    public function apiSearchArticles(Request $request, ArticleRepository $articleRepository): JsonResponse
    {
        $query = $request->query->get('q', '');
        $page = $request->query->getInt('page', 1);
        $limit = 6;

        $articles = $articleRepository->searchWithFilters($query, '', $page, $limit);
        $total = $articleRepository->countSearchResults($query, '');
        $totalPages = ceil($total / $limit);

        $results = [
            'articles' => array_map(function($article) {
                return [
                    'id' => $article->getId(),
                    'title' => $article->getTitle(),
                    'content' => substr($article->getContent(), 0, 150) . '...',
                    'author' => $article->getUser() ? $article->getUser()->getEmail() : 'Anonyme',
                    'date' => $article->getCreatedAt()->format('d/m/Y H:i'),
                    'commentsCount' => count($article->getComments()),
                ];
            }, $articles),
            'pagination' => [
                'current' => $page,
                'total' => $totalPages,
                'hasMore' => $page < $totalPages
            ]
        ];

        return $this->json($results);
    }

    #[Route('/article/{id}/translate', name: 'app_article_translate', methods: ['POST'])]
    public function translate(Article $article): Response
    {
        try {
            $content = $article->getContent();
            
            // Traduire en anglais et en arabe
            $translations = [
                'english' => $this->translationService->translate($content, 'en'),
                'arabic' => $this->translationService->translate($content, 'ar')
            ];

            return $this->json($translations);

        } catch (\Exception $e) {
            return $this->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    #[Route('/translate/{id}/{language}', name: 'article_translate', methods: ['GET'])]
    public function translateArticle(Article $article, string $language): JsonResponse
    {
        try {
            // Vérifier si la langue est supportée
            if (!in_array($language, ['en', 'ar', 'english', 'arabic'])) {
                throw new \Exception('Langue non supportée. Utilisez "en" ou "ar".');
            }

            $content = $article->getContent();
            $translation = $this->translationService->translate($content, $language);
            
            return $this->json([
                'success' => true,
                'translation' => $translation
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 200); // Retourner 200 même en cas d'erreur pour le traitement côté client
        }
    }
}

