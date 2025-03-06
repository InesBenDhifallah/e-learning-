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

#[Route('/articles')]
class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'article_index')]
public function index(ArticleRepository $articleRepository): Response
{
    $articles = $articleRepository->findBy([], ['createdAt' => 'DESC']); // Trier par date de création (du plus récent au plus ancien)

    return $this->render('article/index.html.twig', [
        'articles' => $articles,
    ]);
}


    #[Route('/article/{id}', name: 'article_show', requirements: ['id' => '\d+'])]
    public function show(int $id, ArticleRepository $articleRepository): Response
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

    #[Route('/article/new', name: 'article_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();
        $article->setUser($this->getUser());
        $article->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'L\'article a été créé avec succès.');

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/article/{id}/edit', name: 'article_edit')]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($article->getUser() !== $this->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les droits pour modifier cet article.');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'L\'article a été modifié avec succès.');

            return $this->redirectToRoute('article_index');
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
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();

            $this->addFlash('success', 'L\'article a été supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide, suppression annulée.');
        }

        return $this->redirectToRoute('article_index');
    }
}