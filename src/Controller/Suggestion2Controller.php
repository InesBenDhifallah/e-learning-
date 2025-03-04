<?php

namespace App\Controller;

use App\Entity\Suggestion;
use App\Form\SuggestionType;
use App\Repository\SuggestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Question;
use App\Repository\QuestionRepository;

#[Route('/suggestion2')]
final class Suggestion2Controller extends AbstractController
{
    #[Route('/', name: 'app_suggestion2_index', methods: ['GET'])]
    public function index(SuggestionRepository $suggestionRepository): Response
    {
        return $this->render('suggestion2/index.html.twig', [
            'suggestions' => $suggestionRepository->findAll(),
        ]);
    }

    #[Route('/new/{question_id}/{quizz_id}', name: 'app_suggestion2_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager,
        QuestionRepository $questionRepository,
        int $question_id,
        int $quizz_id
    ): Response
    {
        $question = $questionRepository->find($question_id);
        
        if (!$question) {
            throw $this->createNotFoundException('Question non trouvée');
        }

        $suggestion = new Suggestion();
        $suggestion->setQuestion($question);
        
        $form = $this->createForm(SuggestionType::class, $suggestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($suggestion);
            $entityManager->flush();

            return $this->redirectToRoute('app_question2_show', [
                'id' => $question->getId(),
                'quizz_id' => $quizz_id
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('suggestion2/new.html.twig', [
            'suggestion' => $suggestion,
            'form' => $form,
            'question_id' => $question->getId(),
            'quizz_id' => $quizz_id
        ]);
    }

    #[Route('/{id}/show', name: 'app_suggestion2_show', methods: ['GET'])]
    public function show(Suggestion $suggestion): Response
    {
        return $this->render('suggestion2/show.html.twig', [
            'suggestion' => $suggestion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_suggestion2_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Suggestion $suggestion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SuggestionType::class, $suggestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_question2_show', [
                'id' => $suggestion->getQuestion()->getId(),
                'quizz_id' => $suggestion->getQuestion()->getQuizz()->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('suggestion2/edit.html.twig', [
            'suggestion' => $suggestion,
            'form' => $form,
            'question_id' => $suggestion->getQuestion()->getId(),
            'quizz_id' => $suggestion->getQuestion()->getQuizz()->getId()
        ]);
    }

    #[Route('/{id}/delete/{quizz_id}', name: 'app_suggestion2_delete', methods: ['POST'])]
    public function delete(
        Request $request, 
        Suggestion $suggestion, 
        EntityManagerInterface $entityManager,
        int $quizz_id
    ): Response {
        if ($this->isCsrfTokenValid('delete'.$suggestion->getId(), $request->getPayload()->getString('_token'))) {
            $question_id = $suggestion->getQuestion()->getId();
            
            $entityManager->remove($suggestion);
            $entityManager->flush();

            return $this->redirectToRoute('app_question2_show', [
                'id' => $question_id,
                'quizz_id' => $quizz_id
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->redirectToRoute('app_question2_show', [
            'id' => $suggestion->getQuestion()->getId(),
            'quizz_id' => $quizz_id
        ], Response::HTTP_SEE_OTHER);
    }
}
