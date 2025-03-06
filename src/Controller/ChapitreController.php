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
    #[Route('/chapitre', name: 'app_chapitre_index')]
public function index(ChapitreRepository $chapitreRepository, ModuleRepository $moduleRepository): Response
{
    // Récupérer tous les chapitres
    $chapitres = $chapitreRepository->findAll();

    // Récupérer un module spécifique si nécessaire (par exemple, le premier ou un module particulier)
    $module = $moduleRepository->find(1);  // Exemple : chercher le module avec l'ID 1

    return $this->render('chapitre/index.html.twig', [
        'chapitres' => $chapitres,
        'module' => $module,  // Assurer que le module est passé au template
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

    // src/Controller/ChapitreController.php
#[Route('/chapitre/new/{moduleId}', name: 'app_chapitre_new', methods: ['GET', 'POST'])]
public function new(int $moduleId, ModuleRepository $moduleRepository, Request $request, EntityManagerInterface $entityManager): Response
{
    $module = $moduleRepository->find($moduleId);
    if (!$module) {
        throw $this->createNotFoundException('Module introuvable');
    }

    $chapitre = new Chapitre();
    $chapitre->setModule($module);

    $form = $this->createForm(ChapitreType::class, $chapitre);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($chapitre);
        $entityManager->flush();

        return $this->redirectToRoute('app_chapitre_index');  // Redirection vers la liste des chapitres
    }

    return $this->render('chapitre/new.html.twig', [
        'form' => $form->createView(),
        'module' => $module
    ]);
}

    
}
