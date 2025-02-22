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

#[Route('/Auser')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class AUserController extends AbstractController
{
    #[Route('/articles', name: 'user_articles')]
    public function articles(ArticleRepository $articleRepository): Response
    {
        // Vérifier si l'utilisateur a l'un des rôles requis
        if (!$this->isGranted('ROLE_Parent') && !$this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        $articles = $articleRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('Auser/articles.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}', name: 'user_article_show', methods: ['GET', 'POST'])]
    public function showArticle(
        Article $article, 
        CommentRepository $commentRepository, 
        Request $request, 
        EntityManagerInterface $em
    ): Response {
        // Vérifier si l'utilisateur a l'un des rôles requis
        if (!$this->isGranted('ROLE_Parent') && !$this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        $comment = new Comment();
        $comment->setArticle($article);
        $comment->setCreatedAt(new \DateTimeImmutable());
        $comment->setUser($this->getUser());
        
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->persist($comment);
                $em->flush();
                $this->addFlash('success', 'Commentaire ajouté avec succès.');
                return $this->redirectToRoute('user_article_show', ['id' => $article->getId()]);
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de l\'enregistrement du commentaire.');
            }
        }

        return $this->render('Auser/article_show.html.twig', [
            'article' => $article,
            'comments' => $commentRepository->findBy(['article' => $article], ['createdAt' => 'DESC']),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/comment/{id}/edit', name: 'user_comment_edit')]
    public function editComment(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        // Vérifier si l'utilisateur a l'un des rôles requis
        if (!$this->isGranted('ROLE_Parent') && !$this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        if ($comment->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier ce commentaire.');
        }

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Commentaire modifié avec succès.');
            return $this->redirectToRoute('user_article_show', ['id' => $comment->getArticle()->getId()]);
        }

        return $this->render('Auser/comment_edit.html.twig', [
            'form' => $form->createView(),
            'comment' => $comment,
        ]);
    }

    #[Route('/comment/{id}/delete', name: 'user_comment_delete', methods: ['POST'])]
    public function deleteComment(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
        // Vérifier si l'utilisateur a l'un des rôles requis
        if (!$this->isGranted('ROLE_Parent') && !$this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        if ($comment->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas supprimer ce commentaire.');
        }

        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $em->remove($comment);
            $em->flush();
            $this->addFlash('success', 'Commentaire supprimé avec succès.');
        }

        return $this->redirectToRoute('user_article_show', ['id' => $comment->getArticle()->getId()]);
    }
}

