<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
        $article = $articleRepository->find($id);
    
        if (!$article) {
            throw $this->createNotFoundException('L\'article n\'existe pas.');
        }
    
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/new', name: 'article_new')]
    public function new(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Validation manuelle supplémentaire si nécessaire
            $errors = $validator->validate($article);

            if (count($errors) > 0) {
                // Création d'une chaîne de messages d'erreur
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[] = $error->getMessage();
                }
                
                // Ajout des messages d'erreur dans le flash bag
                foreach ($errorMessages as $errorMessage) {
                    $this->addFlash('error', $errorMessage);
                }
            } elseif ($form->isValid()) {
                // Si la validation réussit
                $entityManager->persist($article);
                $entityManager->flush();

                $this->addFlash('success', 'L\'article a été créé avec succès.');
                return $this->redirectToRoute('article_index');
            }
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $errors = $validator->validate($article);
            $errorMessages = [];
            
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            
            if (count($errorMessages) > 0) {
                foreach ($errorMessages as $errorMessage) {
                    $this->addFlash('error', $errorMessage);
                }
            } elseif ($form->isValid()) {
                $entityManager->flush();
                $this->addFlash('success', 'L\'article a été modifié avec succès.');
                return $this->redirectToRoute('article_index');
            }
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
        ]);
    }

    #[Route('/article/{id}/delete', name: 'article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
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