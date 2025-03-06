<?php 
// src/Controller/CommentController.php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Article;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use DateTimeImmutable;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Service\BadWordsFilter;

#[Route('/admin/comments')]
class CommentController extends AbstractController
{
    private $entityManager;
    private $commentRepository;
    private $validator;
    private $badWordsFilter;

    // Injection des dépendances via le constructeur
    public function __construct(
        EntityManagerInterface $entityManager, 
        CommentRepository $commentRepository, 
        ValidatorInterface $validator,
        BadWordsFilter $badWordsFilter
    ) {
        $this->entityManager = $entityManager;
        $this->commentRepository = $commentRepository;
        $this->validator = $validator;
        $this->badWordsFilter = $badWordsFilter;
    }

    #[Route('/', name: 'admin_comment_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/comment/index.html.twig', [
            'comments' => $this->commentRepository->findBy([], ['createdAt' => 'DESC'])
        ]);
    }

    #[Route('/new', name: 'admin_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setUser($this->getUser());
            
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $this->addFlash('success', 'Commentaire créé avec succès');
            return $this->redirectToRoute('admin_comment_index');
        }

        return $this->render('admin/comment/new.html.twig', [
            'comment' => $comment,
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
            'comment' => $comment,
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
