<?php

namespace App\Controller;

use App\Entity\Chapitre;
use App\Form\ChapitreType;
use App\Repository\ModuleRepository;
use App\Repository\ChapitreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/chapitre')]
class ChapitreController extends AbstractController
{
    #[Route('/', name: 'app_chapitre_index', methods: ['GET'])]
    public function index(ChapitreRepository $chapitreRepository): Response
    {
        return $this->render('chapitre/index.html.twig', [
            'chapitres' => $chapitreRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_chapitre_show', methods: ['GET'])]
    public function show(Chapitre $chapitre): Response
    {
        if (!$this->isGranted('ROLE_Parent') && !$this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        return $this->render('chapitre/show.html.twig', [
            'chapitre' => $chapitre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_chapitre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Chapitre $chapitre, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $form = $this->createForm(ChapitreType::class, $chapitre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_chapitre_index');
        }

        return $this->render('chapitre/edit.html.twig', [
            'chapitre' => $chapitre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_chapitre_delete', methods: ['POST'])]
    public function delete(Request $request, Chapitre $chapitre, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        if ($this->isCsrfTokenValid('delete' . $chapitre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($chapitre);
            $entityManager->flush();
            $this->addFlash('success', "Chapitre supprimé avec succès.");
        }

        return $this->redirectToRoute('app_chapitre_index');
    }

    #[Route('/chapitre/new/{moduleId}', name: 'app_chapitre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, int $moduleId, ModuleRepository $moduleRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer le module correspondant à l'ID
        $module = $moduleRepository->find($moduleId);
        if (!$module) {
            throw $this->createNotFoundException("Module introuvable.");
        }
    
        $chapitre = new Chapitre();
        $chapitre->setModule($module);
        
        // Création du formulaire pour le chapitre
        $form = $this->createForm(ChapitreType::class, $chapitre);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer le chapitre dans la base de données
            $entityManager->persist($chapitre);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_cours_index');  // Redirection vers la liste des cours
        }
    
        return $this->render('chapitre/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
