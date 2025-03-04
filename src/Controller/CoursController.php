<?php

namespace App\Controller;

use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/cours')]
final class CoursController extends AbstractController
{
    #[Route(name: 'app_cours_index', methods: ['GET'])]
    public function index(CoursRepository $coursRepository): Response
    {
        return $this->render('cours/index.html.twig', [
            'cours' => $coursRepository->findAll(),
        ]);
    }

    // Dans la méthode 'new' (pour créer un cours)
#[Route('/new', name: 'app_cours_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    // Vérification si l'utilisateur a le rôle 'ROLE_PROFESSOR'
    if (!$this->isGranted('ROLE_PROFESSOR')) {
        throw $this->createAccessDeniedException('Accès refusé');
    }

    $cour = new Cours();
    $form = $this->createForm(CoursType::class, $cour);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $file = $form->get('contenuFile')->getData();
        if ($file) {
            $cour->setUpdatedAt(new \DateTimeImmutable());
        }
        
        $entityManager->persist($cour);
        $entityManager->flush();

        return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->render('cours/new.html.twig', [
        'form' => $form->createView(),
    ]);
}



    #[Route('/{id}', name: 'app_cours_show', methods: ['GET'])]
    public function show(Cours $cour): Response
    {
        return $this->render('cours/show.html.twig', [
            'cour' => $cour,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_cours_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Cours $cour, EntityManagerInterface $entityManager): Response
    {

        // Vérification si l'utilisateur a le rôle 'ROLE_PROFESSOR'
        if (!$this->isGranted('ROLE_PROFESSOR')) {
            throw $this->createAccessDeniedException('Accès refusé');
        }
    
        $form = $this->createForm(CoursType::class, $cour);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('contenuFile')->getData();
            if ($file) {
                $cour->setUpdatedAt(new \DateTimeImmutable());
            }

    
            $entityManager->flush();
    
            return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
        }
    

        return $this->render('cours/edit.html.twig', [
            'cour' => $cour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_cours_delete', methods: ['POST'])]

public function delete(Request $request, Cours $cour, EntityManagerInterface $entityManager): Response
{
    // Vérification si l'utilisateur a le rôle 'ROLE_PROFESSOR'
    if (!$this->isGranted('ROLE_PROFESSOR')) {
        throw $this->createAccessDeniedException('Accès refusé');
    }

    if ($this->isCsrfTokenValid('delete' . $cour->getId(), $request->request->get('_token'))) {
        $entityManager->remove($cour);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_cours_index', [], Response::HTTP_SEE_OTHER);
}}

