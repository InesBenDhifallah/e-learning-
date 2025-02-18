<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;



class UserController extends AbstractController
{
    #[Route('/user/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'utilisateur actuellement connecté
        $user = $this->getUser();

        // Si aucun utilisateur n'est connecté, rediriger vers la page de connexion
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Créer le formulaire avec les données de l'utilisateur connecté
        $form = $this->createForm(UserType::class, $user);
        
        // Gérer la soumission du formulaire
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder les changements dans la base de données
            $entityManager->flush();

            // Ajouter un message flash de succès
            $this->addFlash('success', 'Votre profil a été mis à jour avec succès.');
            

            // Rediriger vers la même page pour éviter la resoumission du formulaire
            return $this->redirectToRoute('app_user_edit');
        }

        // Afficher le formulaire
        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            
        ]);
    }
}

