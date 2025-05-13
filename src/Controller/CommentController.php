<?php 
// src/Controller/CommentController.php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Article;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\ArticleRepository;
use App\Service\BadWordsFilter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/admin/comments')]
class CommentController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CommentRepository $commentRepository;
    private ValidatorInterface $validator;
    private BadWordsFilter $badWordsFilter;
    private ArticleRepository $articleRepository;

    public function __construct(
        EntityManagerInterface $entityManager, 
        CommentRepository $commentRepository, 
        ValidatorInterface $validator,
        BadWordsFilter $badWordsFilter,
        ArticleRepository $articleRepository
    ) {
        $this->entityManager = $entityManager;
        $this->commentRepository = $commentRepository;
        $this->validator = $validator;
        $this->badWordsFilter = $badWordsFilter;
        $this->articleRepository = $articleRepository;
    }

    #[Route('/', name: 'admin_comment_index', methods: ['GET'])]
    public function index(): Response
    {
        $comments = $this->commentRepository->findAll();
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/new/{articleId}', name: 'admin_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, int $articleId): Response
    {
        $article = $this->articleRepository->find($articleId);

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        $comment = new Comment();
        $comment->setArticle($article);
        $comment->setCreatedAt(new \DateTimeImmutable());

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $this->addFlash('success', 'Commentaire créé avec succès');
            return $this->redirectToRoute('admin_comment_index');
        }

        return $this->render('admin/comment/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_comment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Commentaire modifié avec succès');
            return $this->redirectToRoute('admin_comment_index');
        }

        return $this->render('admin/comment/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_comment_show', methods: ['GET'])]
    public function show(Comment $comment): Response
    {
        return $this->render('admin/comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    #[Route('/{id}/delete', name: 'admin_comment_delete', methods: ['POST'])]
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($comment);
            $this->entityManager->flush();
            $this->addFlash('success', 'Commentaire supprimé avec succès');
        }

        return $this->redirectToRoute('admin_comment_index');
    }
}
