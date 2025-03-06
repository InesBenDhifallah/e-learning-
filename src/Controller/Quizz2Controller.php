<?php

namespace App\Controller;

use App\Entity\Quizz;
use App\Form\Quizz2Type;
use App\Repository\QuizzRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Suggestion;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\QuizzResult;
#[Route('/quizz2')]
final class Quizz2Controller extends AbstractController
{
    #[Route(name: 'app_quizz2_index', methods: ['GET'])]
    public function index(QuizzRepository $quizzRepository): Response
    {
        return $this->render('quizz2/index.html.twig', [
            'quizzes' => $quizzRepository->findAll(),
        ]);
    }

    #[Route('/quizgame', name: 'app_quizz2_list_front', methods: ['GET'])]
    public function listFront(QuizzRepository $quizzRepository): Response
    {
        return $this->render('quizz2/list_front.html.twig', [
            'quizzes' => $quizzRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_quizz2_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quizz = new Quizz();
        $form = $this->createForm(Quizz2Type::class, $quizz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quizz);
            $entityManager->flush();

            $this->addFlash('success', 'Le quiz a été créé avec succès !');

            return $this->redirectToRoute('app_quizz2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quizz2/new.html.twig', [
            'quizz' => $quizz,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_quizz2_show', methods: ['GET'])]
    public function show(Quizz $quizz): Response
    {
        return $this->render('quizz2/show.html.twig', [
            'quizz' => $quizz,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_quizz2_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quizz $quizz, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Quizz2Type::class, $quizz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Le quiz a été mis à jour avec succès !');
            return $this->redirectToRoute('app_quizz2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quizz2/edit.html.twig', [
            'quizz' => $quizz,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/play', name: 'app_quizz2_play', methods: ['GET', 'POST'])]
    public function play(Request $request, Quizz $quizz, EntityManagerInterface $entityManager): Response
    {
        $questions = $quizz->getQuestions();
        $score = 0;
        $totalQuestions = count($questions);
        $results = [];

        // Gestion des réponses
        if ($request->isMethod('POST')) {
            $answers = $request->request->all();

            foreach ($questions as $question) {
                $answerId = $answers['question_' . $question->getId()] ?? null;
                if ($answerId) {
                    $selectedSuggestion = $entityManager->getRepository(Suggestion::class)->find($answerId);
                    $results[$question->getId()] = [
                        'question' => $question->getQuestion(),
                        'selected' => $selectedSuggestion->getContenu(),
                        'correct' => $selectedSuggestion->getEstCorrecte(),
                    ];
                    if ($selectedSuggestion->getEstCorrecte()) {
                        $score++;
                    }
                }
            }

            // Calcul du pourcentage du score
            $percentage = ($totalQuestions > 0) ? ($score / $totalQuestions) * 100 : 0;

            // Mise à jour du meilleur score
            if (!$quizz->getBest() || $percentage > floatval($quizz->getBest())) {
                $quizz->setBest(strval($percentage));
                $entityManager->flush();
            }
            $user = $this->getUser();
            if ($user) {
                $quizzResult = new QuizzResult();
                $quizzResult->setUser($user);
                $quizzResult->setQuizz($quizz);
                $quizzResult->setScore($percentage);
                $entityManager->persist($quizzResult);
                $entityManager->flush();
            }

            return $this->render('quizz2/results.html.twig', [
                'quizz' => $quizz,
                'score' => $score,
                'total' => $totalQuestions,
                'percentage' => $percentage,
                'results' => $results
            ]);
        }

        return $this->render('quizz2/play.html.twig', [
            'quizz' => $quizz,
            'questions' => $questions,
            'score' => $score,
        ]);
    }

    #[Route('/{id}', name: 'app_quizz2_delete', methods: ['POST'])]
    public function delete(Request $request, Quizz $quizz, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quizz->getId(), $request->request->get('_token'))) {
            $entityManager->remove($quizz);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_quizz2_index', [], Response::HTTP_SEE_OTHER);
    }
}
