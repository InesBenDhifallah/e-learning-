<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\Question;
use App\Entity\Quizz;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quizz')]
class QuizzController extends AbstractController
{

    #[Route('/switch/{role}', name: 'switch_role')]
    public function switchRole(string $role, SessionInterface $session): Response
    {
        if (!in_array($role, ['admin', 'eleve'])) {
            throw $this->createNotFoundException("Rôle invalide.");
        }

        $session->set('role', $role);
        return $this->redirectToRoute('quizz_index');
    }
    // Page with all quizzes
    #[Route('/', name: 'quizz_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $quizzs = $entityManager->getRepository(Quizz::class)->findAll();
        $role = $session->get('role', 'admin'); // Par défaut Admin

        return $this->render('quizz/index.html.twig', [
            'quizzs' => $quizzs,
            'role' => $role,
        ]);
    }

    // Page to create a new quiz
    #[Route('/new', name: 'quizz_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            $matiere = $request->request->get('matiere');
            $chapitre = $request->request->get('chapitre');
            $difficulte = $request->request->get('difficulte');

            if (!$matiere || !$chapitre || !$difficulte) {
                $this->addFlash('error', 'Veuillez remplir tous les champs.');
                return $this->redirectToRoute('quizz_new');
            }

            $quizz = new Quizz();
            $quizz->setMatiere($matiere);
            $quizz->setChapitre((int)$chapitre);
            $quizz->setDifficulte((int)$difficulte);
            $quizz->setEtat(true); // Par défaut actif
            $quizz->setGain(0); // Initialisation à zéro
            $quizz->setBestg(0); // Initialisation à zéro

            $entityManager->persist($quizz);
            $entityManager->flush();

            return $this->redirectToRoute('quizz_index');
        }

        return $this->render('quizz/add.html.twig');
    }

    // Page to show the details of a specific quiz
    #[Route('/{id}', name: 'quizz_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Quizz $quizz, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $questions = $entityManager->getRepository(Question::class)->findBy(['idq' => $quizz]);
        $role = $session->get('role', 'admin');

        // 🔹 Enregistrement des réponses (pour l'élève)
        if ($request->isMethod('POST') && $role === 'eleve') {
            foreach ($questions as $question) {
                $userResponse = $request->request->get('question_' . $question->getId());
                if ($userResponse !== null) {
                    $question->setSolution($userResponse); // Mise à jour de la réponse
                    $entityManager->persist($question);
                }
            }
            $entityManager->flush();
            $this->addFlash('success', 'Réponses enregistrées !');
            return $this->redirectToRoute('quizz_show', ['id' => $quizz->getId()]);
        }

        return $this->render('quizz/show.html.twig', [
            'quiz' => $quizz,
            'questions' => $questions,
            'role' => $role,
        ]);
    }


    // Route to delete a quiz
    #[Route('/{id}/delete', name: 'quizz_delete', methods: ['POST'])]
    public function delete(Request $request, Quizz $quizz, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $quizz->getId(), $request->request->get('_token'))) {
            $entityManager->remove($quizz);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quizz_index');
    }

    // Route to add a question to a quiz
    // Route pour afficher le formulaire d'ajout de question
    #[Route('/{id}/question/add', name: 'question_add', methods: ['GET', 'POST'])]
    public function addQuestion(Request $request, Quizz $quizz, EntityManagerInterface $entityManager): Response
    {
        $question = new Question();
        $question->setIdq($quizz); // Associer la question au quiz

        // Formulaire d'ajout de question
        if ($request->isMethod('POST')) {
            $questionText = $request->request->get('question');
            $reponse = $request->request->get('reponse');
            $solution = $request->request->get('solution');

            if (!$questionText || !$reponse || !$solution) {
                $this->addFlash('error', 'Veuillez remplir tous les champs de la question.');
                return $this->redirectToRoute('question_add', ['id' => $quizz->getId()]);
            }

            $question->setQuestion($questionText);
            $question->setReponse($reponse);
            $question->setSolution($solution);

            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('quizz_show', ['id' => $quizz->getId()]);
        }

        return $this->render('quizz/add_question.html.twig', [
            'quiz' => $quizz,
        ]);
        
    }

}
