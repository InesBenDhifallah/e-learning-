<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quizz;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    // Route pour ajouter une question à un quiz spécifique
    #[Route('/quizz/{id}/question/add', name: 'question_add', methods: ['POST'])]
    public function addQuestion(Request $request, Quizz $quizz, EntityManagerInterface $entityManager): Response
    {
        $questionText = $request->request->get('question');
        $reponse = $request->request->get('reponse');
        $solution = $request->request->get('solution');

        // Validation des champs
        if (!$questionText || !$reponse || !$solution) {
            $this->addFlash('error', 'Veuillez remplir tous les champs de la question.');
            return $this->redirectToRoute('quizz_show', ['id' => $quizz->getId()]);
        }

        // Créer la nouvelle question
        $question = new Question();
        $question->setQuestion($questionText);
        $question->setReponse($reponse);
        $question->setSolution($solution);
        $question->setIdq($quizz); // Associer la question au quiz

        // Persister la question
        $entityManager->persist($question);
        $entityManager->flush();

        $this->addFlash('success', 'La question a été ajoutée avec succès.');
        
        return $this->redirectToRoute('quizz_show', ['id' => $quizz->getId()]);
    }
}
