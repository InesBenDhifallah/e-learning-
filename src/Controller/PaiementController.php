<?php

namespace App\Controller;

use App\Entity\Paiement;
use App\Entity\Abonnement;
use App\Form\PaiementType;
use App\Repository\AbonnementRepository;
use App\Repository\PaiementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/paiement')]
final class PaiementController extends AbstractController
{
    
    #[Route('/abonnement', name: 'app_abonnement_index', methods: ['GET'])]
    public function abonnementIndex(AbonnementRepository $abonnementRepository): Response
    {
        return $this->render('abonnement/abonnement.html.twig', [
            'abonnements' => $abonnementRepository->findAll(),
        ]);
    }

    
    #[Route('/new/{abonnementId}', name: 'app_paiement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, AbonnementRepository $abonnementRepository, int $abonnementId): Response
    {
        // Récupérer l'abonnement sélectionné
        $abonnement = $abonnementRepository->find($abonnementId);
        if (!$abonnement) {
            throw $this->createNotFoundException("L'abonnement n'existe pas.");
        }
        $montant=$abonnement->getPrix();

        
        $paiement = new Paiement();
        $paiement->setIdAbonnement($abonnement);
        $paiement->setMontant($montant);
    
        
        $paiement->setDatePaiement(new \DateTime()); 

        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paiement);
            $entityManager->flush();

            return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('paiement/new.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
            'montant'=>$montant,
        ]);
    }

    #[Route('/index', name: 'app_paiement_index', methods: ['GET'])]
    public function index(PaiementRepository $paiementRepository): Response
    {
        
        return $this->render('paiement/index.html.twig', [
            'paiements' => $paiementRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_paiement_show', methods: ['GET'])]
    public function show(Paiement $paiement): Response
    {
        return $this->render('paiement/show.html.twig', [
            'paiement' => $paiement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_paiement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Paiement $paiement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('paiement/edit.html.twig', [
            'paiement' => $paiement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_paiement_delete', methods: ['POST'])]
    public function delete(Request $request, Paiement $paiement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paiement->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($paiement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
    }
}
