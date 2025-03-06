<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Entity\Chapitre;
use App\Form\CoursType;
use App\Repository\ModuleRepository;
use App\Repository\CoursRepository;
use App\Repository\ChapitreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/cours')]
class CoursController extends AbstractController
{
    #[Route('/', name: 'app_cours_index', methods: ['GET'])]
    public function index(ModuleRepository $moduleRepository, ChapitreRepository $chapitreRepository, CoursRepository $coursRepository)
    {
        $modules = $moduleRepository->findAll();

        $modulesWithChapitresAndCours = [];
        foreach ($modules as $module) {
            $chapitres = $chapitreRepository->findBy(['module' => $module]);
            $cours = [];
            foreach ($chapitres as $chapitre) {
                $cours[$chapitre->getId()] = $coursRepository->findBy(['chapitre' => $chapitre]);
            }
            $modulesWithChapitresAndCours[] = [
                'module' => $module,
                'chapitres' => $chapitres,
                'cours' => $cours,
            ];
        }

        return $this->render('Cours/index.html.twig', [
            'modulesWithChapitresAndCours' => $modulesWithChapitresAndCours,
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/new/{chapitreId}', name: 'app_cours_new', methods: ['GET', 'POST'])]
    public function new(Request $request, int $chapitreId, ChapitreRepository $chapitreRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if (!$this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $chapitre = $chapitreRepository->find($chapitreId);
        if (!$chapitre) {
            throw $this->createNotFoundException("Chapitre introuvable.");
        }

        $cours = new Cours();
        $cours->setChapitre($chapitre);
        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('contenuFile')->getData();
            if ($file) {
                $newFilename = $slugger->slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) 
                                . '-' . uniqid() . '.' . $file->guessExtension();

                try {
                    $file->move($this->getParameter('uploads_directory'), $newFilename);
                    $cours->setContenuFichier($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', "Erreur lors de l'upload du fichier.");
                }

                $cours->setUpdatedAt(new \DateTimeImmutable());
            }

            $entityManager->persist($cours);
            $entityManager->flush();

            return $this->redirectToRoute('app_cours_index');
        }

        return $this->render('cours/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_cours_show', methods: ['GET'])]
    public function show(Cours $cours): Response
    {
        if (!$this->isGranted('ROLE_Parent') && !$this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        return $this->render('cours/show.html.twig', [
            'cours' => $cours,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cours $cours, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        $form = $this->createForm(CoursType::class, $cours);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('contenuFile')->getData();
            if ($file) {
                $cours->setUpdatedAt(new \DateTimeImmutable());
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_cours_index');
        }

        return $this->render('cours/edit.html.twig', [
            'cours' => $cours,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_cours_delete', methods: ['POST'])]
    public function delete(Request $request, Cours $cours, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_TEACHER')) {
            throw $this->createAccessDeniedException('Accès refusé.');
        }

        if ($this->isCsrfTokenValid('delete' . $cours->getId(), $request->request->get('_token'))) {
            $entityManager->remove($cours);
            $entityManager->flush();
            $this->addFlash('success', "Cours supprimé avec succès.");
        }

        return $this->redirectToRoute('app_cours_index');
    }
}
