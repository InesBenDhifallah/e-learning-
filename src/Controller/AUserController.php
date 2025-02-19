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

class AUserController extends AbstractController
{
   
    #[Route('/Auser/articles', name: 'user_articles')]
    public function articles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('Auser/articles.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/Auser/article/{id}', name: 'user_article_show')]
    public function showArticle(Article $article, CommentRepository $commentRepository, Request $request, EntityManagerInterface $em): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setArticle($article);
            $comment->setCreatedAt(new \DateTimeImmutable());
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Commentaire ajouté avec succès.');
            return $this->redirectToRoute('user_article_show', ['id' => $article->getId()]);
        }

        return $this->render('Auser/article_show.html.twig', [
            'article' => $article,
            'comments' => $commentRepository->findBy(['article' => $article], ['createdAt' => 'DESC']),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/Auser/comment/{id}/edit', name: 'user_comment_edit')]
    public function editComment(Comment $comment, Request $request, EntityManagerInterface $em): Response
    {
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

    #[Route('/Auser/comment/{id}/delete', name: 'user_comment_delete', methods: ['POST'])]
    public function deleteComment(Comment $comment, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $em->remove($comment);
            $em->flush();
            $this->addFlash('success', 'Commentaire supprimé avec succès.');
        }

        return $this->redirectToRoute('user_article_show', ['id' => $comment->getArticle()->getId()]);
    }
}
