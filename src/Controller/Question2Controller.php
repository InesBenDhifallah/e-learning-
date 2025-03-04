<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Quizz;
use App\Repository\QuizzRepository;

#[Route('/question2')]
final class Question2Controller extends AbstractController
{
    #[Route('/{quizz_id}/{id<\d+>}', name: 'app_question2_show', methods: ['GET'])]
    public function show(QuestionRepository $questionRepository, int $quizz_id, string|int $id): Response

{
    // Charger la question avec ses suggestions
    $question = $questionRepository->createQueryBuilder('q')
        ->leftJoin('q.suggestions', 's')
        ->addSelect('s')
        ->where('q.id = :question_id')
        ->setParameter('question_id', $id)
        ->getQuery()
        ->getOneOrNullResult();

    if (!$question) {
        throw $this->createNotFoundException('Question non trouvée.');
    }

    return $this->render('question2/show.html.twig', [
        'question' => $question,
        'quizz_id' => $quizz_id
    ]);
}


#[Route('/{quizz_id}', name: 'app_question2_index', methods: ['GET'])]
public function index(QuestionRepository $questionRepository, int $quizz_id): Response
{
    $questions = $questionRepository->findBy(['quizz' => $quizz_id]);

    return $this->render('question2/index.html.twig', [
        'questions' => $questions,
        'quizz_id' => $quizz_id
    ]);
}
    #[Route('/{quizz_id}/new', name: 'app_question2_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager, 
        QuizzRepository $quizzRepository,
        int $quizz_id
    ): Response
    {
        $quizz = $quizzRepository->find($quizz_id);
        
        if (!$quizz) {
            throw $this->createNotFoundException('Quiz non trouvé');
        }
    
        $question = new Question();
        $question->setQuizz($quizz);
        
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($question);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_question2_index', ['quizz_id' => $quizz->getId()], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('question2/new.html.twig', [
            'question' => $question,
            'form' => $form,
            'quizz_id' => $quizz->getId()
        ]);
    }
    

    // Modifier une question
    #[Route('/{quizz_id}/{id}/edit', name: 'app_question2_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $question, EntityManagerInterface $entityManager, int $quizz_id): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_question2_index', ['quizz_id' => $quizz_id], Response::HTTP_SEE_OTHER);
        }

        return $this->render('question2/edit.html.twig', [
            'question' => $question,
            'form' => $form,
            'quizz_id' => $quizz_id
        ]);
    }

    // Supprimer une question
    #[Route('/{quizz_id}/{id}/delete', name: 'app_question2_delete', methods: ['POST'])]
    public function delete(Request $request, Question $question, EntityManagerInterface $entityManager, int $quizz_id): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_question2_index', ['quizz_id' => $quizz_id], Response::HTTP_SEE_OTHER);
    }
}
