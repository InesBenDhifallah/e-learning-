<?php

namespace App\Controller;

use App\Entity\Paiement;
use App\Form\FormpaiementType;
use App\Repository\AbonnementRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PayController extends AbstractController
{
    #[Route('/pay/{prix}/{id}', name: 'pay')]
    public function index($prix,$id): Response
    {
        $form = $this->createForm(FormpaiementType::class);

        return $this->render('pay/paiement.html.twig', [
            'form' => $form->createView(),
            'prix' => $prix,
            'id'=>$id,
        ]);
    }
    #[Route('/addpay/{prix}/{id}', name: 'addpay')]
    public function addpaiement($prix,$id,ManagerRegistry $doctrine,Request $request,AbonnementRepository $abonnementRepository): Response
    {

        $abonnement=$abonnementRepository->find($id);
        $paiement=new Paiement();
        $paiement->setMontant($prix);
        $paiement->setDatePaiement(new \DateTime());
        $paiement->setIdAbonnement($abonnement);
        $form= $this->createForm(FormpaiementType::class,$paiement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em=$doctrine->getManager();
            $em->persist($paiement);
            $em->flush();
            return $this->render('succes.html.twig');
        }
        return  $this->render('pay/paiement.html.twig', [
            'form' => $form->createView(),
        ]); 
    }

}
