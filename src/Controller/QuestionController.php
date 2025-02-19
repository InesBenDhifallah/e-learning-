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
    #[Route('/quizz/{id}/question/add', name: 'question_add', methods: ['GET', 'POST'])]
    public function addQuestion(Request $request, Quizz $quizz, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $questionText = $request->request->get('question');
            $suggestionsData = $request->request->get('suggestions'); // Tableau des suggestions
            $correctIndex = $request->request->get('correct_index'); // Index de la bonne réponse

            // 🔹 Vérifier si les données sont bien envoyées
            dump($questionText, $suggestionsData, $correctIndex);
            die(); // Arrête l'exécution pour voir les données

            if (!$questionText || empty($suggestionsData) || $correctIndex === null) {
                $this->addFlash('error', 'Veuillez remplir tous les champs.');
                return $this->redirectToRoute('question_add', ['id' => $quizz->getId()]);
            }

            $question = new Question();
            $question->setQuestion($questionText);
            $question->setIdq($quizz);

            $entityManager->persist($question);

            $suggestions = [];
            foreach ($suggestionsData as $index => $text) {
                $suggestion = new Suggestion();
                $suggestion->setContenu($text);
                $suggestion->setQuestion($question);
                $suggestion->setEstCorrecte($index == $correctIndex); // Définir si c'est la bonne réponse

                $entityManager->persist($suggestion);
                $suggestions[] = $suggestion;
            }

            // Définir la solution correcte dans la question
            $question->setSolution($suggestions[$correctIndex]);

            $entityManager->flush();

            return $this->redirectToRoute('quizz_show', ['id' => $quizz->getId()]);
        }

        return $this->render('question/add.html.twig', [
            'quiz' => $quizz
        ]);
    }

    #[Route('/quizz/{id}/questions', name: 'quizz_questions', methods: ['GET'])]
    public function showQuestions(Quizz $quizz, EntityManagerInterface $entityManager): Response
    {
        $questions = $entityManager->getRepository(Question::class)->findBy(['idq' => $quizz]);

        return $this->render('question/index.html.twig', [
            'quiz' => $quizz,
            'questions' => $questions,
        ]);
    }
    #[Route('/quizz/{id}/questions/add', name: 'question_add', methods: ['POST'])]
    public function addMultipleQuestions(Request $request, Quizz $quizz, EntityManagerInterface $entityManager): Response
    {
        $questionsData = $request->request->all('questions');

        foreach ($questionsData as $questionIndex => $data) {
            if (empty($data['question']) || empty($data['suggestions']) || !isset($data['correct_index'])) {
                continue; // Ignore les entrées incomplètes
            }

            // 🔹 Création de la question
            $question = new Question();
            $question->setQuestion($data['question']);
            $question->setIdq($quizz);
            $entityManager->persist($question);

            $suggestions = [];
            foreach ($data['suggestions'] as $index => $suggestionText) {
                $suggestion = new Suggestion();
                $suggestion->setContenu($suggestionText);
                $suggestion->setQuestion($question);
                $suggestion->setEstCorrecte($index == $data['correct_index']); // Définir la réponse correcte

                $entityManager->persist($suggestion);
                $suggestions[] = $suggestion;
            }

            // 🔹 Associer la solution correcte à la question
            $question->setSolution($suggestions[$data['correct_index']]);
            $entityManager->persist($question);
        }

        $entityManager->flush();
        return $this->redirectToRoute('quizz_show', ['id' => $quizz->getId()]);
    }


}
