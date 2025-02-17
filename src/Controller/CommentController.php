<?php 
// src/Controller/CommentController.php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Article;
use App\Form\CommentType;
use App\Repository\ArticleRepository;  // Utilisation correcte du namespace pour le repository
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    private $entityManager;
    private $articleRepository;

    // Injection des dépendances via le constructeur
    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        $this->entityManager = $entityManager;
        $this->articleRepository = $articleRepository;
    }

    // Créer un commentaire pour un article
    #[Route('/article/{articleId}/comment/create', name: 'comment_create')]
    public function create(int $articleId, Request $request): Response
    {
        $article = $this->articleRepository->find($articleId);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $comment = new Comment();
        $comment->setArticle($article);  // Associer le commentaire à l'article
        $comment->setCreatedAt(new \DateTime()); // Définir la date de création du commentaire

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder le commentaire
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            // Rediriger après la soumission
            return $this->redirectToRoute('article_show', ['id' => $article->getId()]);
        }

        return $this->render('comment/create.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    // Modifier un commentaire
    #[Route('/comment/{id}/edit', name: 'comment_edit')]
    public function edit(Comment $comment, Request $request): Response
    {
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder le commentaire modifié
            $this->entityManager->flush();

            return $this->redirectToRoute('article_show', ['id' => $comment->getArticle()->getId()]);
        }

        return $this->render('comment/edit.html.twig', [
            'form' => $form->createView(),
            'comment' => $comment,
        ]);
    }

    // Supprimer un commentaire
    #[Route('/comment/{id}/delete', name: 'comment_delete')]
    public function delete(Comment $comment): Response
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();

        return $this->redirectToRoute('article_show', ['id' => $comment->getArticle()->getId()]);
    }

    // Afficher un commentaire
    #[Route('/comment/{id}', name: 'comment_show')]
    public function show(Comment $comment): Response
    {
        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }
}
