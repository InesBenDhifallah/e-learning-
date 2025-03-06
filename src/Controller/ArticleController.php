<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Entity\ArticleCategory;

#[Route('/articles')]
class ArticleController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'articles_index', methods: ['GET'])]
    public function index(Request $request, ArticleRepository $articleRepository): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 6; // Articles par page
        $search = $request->query->get('search');
        $category = $request->query->get('category');

        if ($search || $category) {
            $articles = $articleRepository->searchWithFilters($search, $category, $page, $limit);
            $total = $articleRepository->countSearchResults($search, $category);
        } else {
            $articles = $articleRepository->findPaginated($page, $limit);
            $total = $articleRepository->count([]);
        }

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'currentPage' => $page,
            'totalPages' => ceil($total / $limit),
            'categories' => ArticleCategory::CATEGORIES,
            'currentCategory' => $category,
            'currentSearch' => $search
        ]);
    }

    #[Route('/stats', name: 'articles_stats', methods: ['GET'])]
    public function showStats(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();
        
        // Initialiser les tableaux de statistiques
        $articles_per_month = array_fill(0, 12, 0);
        $categories = [];
        $total_comments = 0;
        $total_articles = count($articles);
        $recent_articles = 0;
        $most_commented_article = null;
        $max_comments = 0;
        
        // Date il y a 30 jours
        $thirtyDaysAgo = new \DateTimeImmutable('-30 days');
        
        foreach ($articles as $article) {
            // Stats par catégorie
            if ($article->getCategory()) {
                $category = $article->getCategory();
                if (!isset($categories[$category])) {
                    $categories[$category] = 0;
                }
                $categories[$category]++;
            }
            
            // Articles par mois
            $month = $article->getCreatedAt()->format('n') - 1; // 0-11 pour les mois
            $articles_per_month[$month]++;
            
            // Compter les commentaires
            $commentCount = count($article->getComments());
            $total_comments += $commentCount;
            
            // Trouver l'article le plus commenté
            if ($commentCount > $max_comments) {
                $max_comments = $commentCount;
                $most_commented_article = $article;
            }
            
            // Articles récents (30 derniers jours)
            if ($article->getCreatedAt() > $thirtyDaysAgo) {
                $recent_articles++;
            }
        }

        // Calculer les moyennes
        $avg_comments = $total_articles > 0 ? round($total_comments / $total_articles, 2) : 0;
        
        return $this->render('article/api/stats.html.twig', [
            'total_articles' => $total_articles,
            'total_comments' => $total_comments,
            'avg_comments' => $avg_comments,
            'recent_articles' => $recent_articles,
            'most_commented_article' => $most_commented_article,
            'max_comments' => $max_comments,
            'categories' => $categories,
            'articles_per_month' => $articles_per_month
        ]);
    }

    #[Route('/new', name: 'article_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $article = new Article();
        $article->setUser($this->getUser());
        $article->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($article);
            $this->entityManager->flush();

            $this->addFlash('success', 'Article créé avec succès!');
            return $this->redirectToRoute('articles_index');
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article): Response
    {
        if ($article->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits pour modifier cet article.');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Article modifié avec succès.');
            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}', name: 'article_show', methods: ['GET', 'POST'])]
    public function show(Article $article, Request $request): Response
    {
        $comment = new Comment();
        $comment->setArticle($article);
        $comment->setUser($this->getUser());
        
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $this->addFlash('success', 'Commentaire ajouté avec succès!');
            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        }

        // Récupérer tous les commentaires triés par date
        $comments = $this->entityManager->getRepository(Comment::class)
            ->findBy(['article' => $article], ['createdAt' => 'DESC']);

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'commentForm' => $commentForm->createView(),
            'comments' => $comments,
            'isAdmin' => $this->isGranted('ROLE_ADMIN')
        ]);
    }

    #[Route('/{id}/delete', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article): Response
    {
        if ($article->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits pour supprimer cet article.');
        }

        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($article);
            $this->entityManager->flush();
            $this->addFlash('success', 'Article supprimé avec succès.');
        }

        return $this->redirectToRoute('articles_index');
    }

    #[Route('/articles/view', name: 'articles_view')]
    public function viewArticles(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/api/index.html.twig', [
            'articles' => $articleRepository->findAllWithComments()
        ]);
    }

    #[Route('/articles/search', name: 'articles_search', methods: ['GET'])]
    public function search(Request $request, ArticleRepository $articleRepository): Response
    {
        $query = $request->query->get('q');
        $articles = $articleRepository->searchByTitle($query);

        return $this->render('article/search.html.twig', [
            'articles' => $articles,
            'query' => $query
        ]);
    }

    #[Route('/comment/{id}/update', name: 'comment_update', methods: ['POST'])]
    public function updateComment(Comment $comment, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si l'utilisateur est l'auteur du commentaire ou un admin
        if ($comment->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits pour modifier ce commentaire.');
        }

        $content = $request->request->get('content');
        if (!empty($content)) {
            $comment->setContent($content);
            $entityManager->flush();
            $this->addFlash('success', 'Commentaire modifié avec succès.');
        }

        return $this->redirectToRoute('articles_show', ['id' => $comment->getArticle()->getId()]);
    }

    #[Route('/comment/{id}/delete', name: 'admin_comment_delete', methods: ['POST'])]
    public function adminDeleteComment(Comment $comment, Request $request): Response
    {
        // Vérifier si l'utilisateur est admin
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Accès refusé');
        }

        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $articleId = $comment->getArticle()->getId();
            $this->entityManager->remove($comment);
            $this->entityManager->flush();
            $this->addFlash('success', 'Commentaire supprimé avec succès');
            return $this->redirectToRoute('article_show', ['id' => $articleId]);
        }

        return $this->redirectToRoute('article_show', ['id' => $comment->getArticle()->getId()]);
    }

    #[Route('/dashboard', name: 'articles_dashboard', methods: ['GET'])]
    public function dashboard(): Response
    {
        return $this->render('article/api/dashboard.html.twig');
    }

    #[Route('/api/search', name: 'articles_api_search', methods: ['GET'])]
    public function apiSearch(Request $request, ArticleRepository $articleRepository): JsonResponse
    {
        $query = $request->query->get('q', '');
        $category = $request->query->get('category', '');
        $page = $request->query->getInt('page', 1);
        $limit = 6; // Nombre d'articles par page
        
        $articles = $articleRepository->searchWithFilters($query, $category, $page, $limit);
        $total = $articleRepository->countSearchResults($query, $category);
        $totalPages = ceil($total / $limit);
        
        $results = [
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
                'total' => $totalPages,
                'hasMore' => $page < $totalPages
            ]
        ];

        return $this->json($results);
    }

    #[Route('/article/{id}/comment', name: 'article_comment_new', methods: ['POST'])]
    public function addComment(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour commenter.');
        }

        $content = $request->request->get('content');
        if (!$content) {
            $this->addFlash('error', 'Le commentaire ne peut pas être vide.');
            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        }

        $comment = new Comment();
        $comment->setContent($content);
        $comment->setUser($this->getUser());
        $comment->setArticle($article);
        $comment->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($comment);
        $entityManager->flush();

        $this->addFlash('success', 'Commentaire ajouté avec succès.');
        return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
    }

    #[Route('/comment/{id}/edit', name: 'comment_edit', methods: ['GET', 'POST'])]
    public function editComment(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        // Check if user is allowed to edit the comment
        if (!$this->getUser() || ($this->getUser() !== $comment->getUser() && !$this->isGranted('ROLE_ADMIN'))) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier ce commentaire.');
        }

        if ($request->isMethod('POST')) {
            $content = $request->request->get('content');
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

    #[Route('/comment/{id}/delete', name: 'comment_delete', methods: ['GET', 'POST'])]
    public function deleteComment(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        // Check if user is allowed to delete the comment
        if (!$this->getUser() || ($this->getUser() !== $comment->getUser() && !$this->isGranted('ROLE_ADMIN'))) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer ce commentaire.');
        }

        // Store the article ID before removing the comment
        $articleId = $comment->getArticle()->getId();
        
        $entityManager->remove($comment);
        $entityManager->flush();
        
        $this->addFlash('success', 'Commentaire supprimé avec succès.');
        
        return $this->redirectToRoute('article_show', ['id' => $articleId]);
    }
}