<?php

namespace App\Controller;

use App\Service\StripeService;
use App\Entity\Paiement;
use App\Entity\Abonnement;
use App\Entity\User;
use App\Form\PaiementType;
use App\Repository\AbonnementRepository;
use App\Repository\PaiementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/paiement')]
final class PaiementController extends AbstractController
{
    

    // Le constructeur qui permet d'injecter StripeService
    private StripeService $stripeService;
    private EntityManagerInterface $entityManager;

    // Injection des deux services dans le constructeur
    public function __construct(StripeService $stripeService, EntityManagerInterface $entityManager)
    {
        $this->stripeService = $stripeService;
        $this->entityManager = $entityManager;
    }



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
        // Trouver l'abonnement
        $abonnement = $abonnementRepository->find($abonnementId);
        if (!$abonnement) {
            throw $this->createNotFoundException("L'abonnement n'existe pas");
        }
    
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour effectuer un paiement.');
        }
    
        // Créer une session Stripe
        $session = $this->stripeService->createCheckoutSession($abonnement, $user);
    
        // Rediriger l'utilisateur vers la page de paiement Stripe
        return $this->redirect($session->url);  // Redirection vers Stripe
    }
    #[Route('/success', name: 'payment_success', methods: ['GET', 'POST'])]
    public function success(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupère le session_id depuis les données envoyées dans la requête POST
        $sessionId = $request->query->get('session_id');

    
        if (!$sessionId) {
            return new Response('Session ID manquant', 400);
        }
    
        \Stripe\Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']); // Utilisation de la clé API depuis .env
    
        try {
            // Récupère la session Stripe pour vérifier les informations
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
    
            // Vérifie que le paiement a été effectué
            if ($session->payment_status === 'paid') {
                // Récupère l'ID de l'abonnement et de l'utilisateur à partir des métadonnées
                $abonnementId = $session->metadata->abonnement_id;
                $userId = $session->metadata->user_id;
    
                // Récupère l'abonnement et l'utilisateur de la base de données
                $abonnement = $entityManager->getRepository(Abonnement::class)->find($abonnementId);
                $user = $entityManager->getRepository(User::class)->find($userId);
    
                if (!$abonnement || !$user) {
                    return new Response('Abonnement ou utilisateur non trouvé.', 400);
                }
    
                // Crée l'objet Paiement et l'enregistre dans la base de données
                $paiement = new Paiement();
                $paiement->setStripeSessionId($sessionId);
                $paiement->setMontant($session->amount_total / 100); // Montant en euros
                $paiement->setDatePaiement(new \DateTime());
                $paiement->setUserid($user); // Assurez-vous que vous avez une relation avec l'utilisateur dans votre entité Paiement
                $paiement->setIdAbonnement($abonnement); // Assurez-vous que vous avez une relation avec l'abonnement dans votre entité Paiement
    
                // Persiste le paiement dans la base de données
                $entityManager->persist($paiement);
                $entityManager->flush();
    
                // Retourne une réponse de succès
                return new Response('Paiement réussi et enregistré !');
            } else {
                return new Response('Le paiement n\'a pas été validé.', 400);
            }
        } catch (\Exception $e) {
            // Gère les erreurs, par exemple si la session est introuvable
            return new Response('Erreur lors du traitement du paiement : ' . $e->getMessage(), 500);
        }
    }
    

    // Route pour la page d'annulation
    #[Route('/payment/cancel', name: 'payment_cancel', methods: ['GET'])]
    public function cancel(): Response
    {
        // Logique en cas d'annulation du paiement
        return new Response('Paiement annulé!');
    }
    
    
    #[Route('/index', name: 'app_paiement_index', methods: ['GET'])]
    public function index(PaiementRepository $paiementRepository, UserInterface $user): Response
    {
        $paiements = $paiementRepository->findBy(['userid' => $user]);

        return $this->render('paiement/index.html.twig', [
            'paiements' => $paiements,
        ]);
    }

   // #[Route('/{id}', name: 'app_paiement_show', methods: ['GET'])]
   // public function show(Paiement $paiement): Response
  //  {
      //  return $this->render('paiement/show.html.twig', [
        //    'paiement' => $paiement,
      //  ]);
   // }

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
        if ($this->isCsrfTokenValid('delete' . $paiement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($paiement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_paiement_index', [], Response::HTTP_SEE_OTHER);
    }
}
