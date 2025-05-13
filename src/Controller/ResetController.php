<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\EmailService;

final class ResetController extends AbstractController
{
       private $emailService; // Déclaration de la propriété
    
        // Injection du service EmailService via le constructeur
        public function __construct(EmailService $emailService)
        {
            $this->emailService = $emailService; // Assigner le service à la propriété
        }
    #[Route('/reset', name: 'app_reset')]
    public function index(): Response
    {
        return $this->render('login/reset.html.twig', [
            'controller_name' => 'ResetController',
        ]);
    }



    #[Route('/send', name: 'app_send')]
    public function sendEmail(Request $request): RedirectResponse
    {
        // Récupérer l'email saisi dans le formulaire
        $email = $request->request->get('email');
    
        // Vérifier si l'email est valide
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Create the reset link with the email as a query parameter
            $resetLink = sprintf('http://127.0.0.1:8000/reset-password?email=%s', urlencode($email));
    
            // Envoi de l'email avec la fonction 'sendMail'
            $subject = 'Réinitialisation de mot de passe AlphaEducation';
            $message = sprintf(
                'You requested a password reset. Click the link below to reset your password: %s',
                $resetLink
            );
            $this->emailService->sendMail($email, $subject, $message);
    
            // Message flash de succès
            $this->addFlash('success', 'L\'email a été envoyé avec succès.');
        } else {
            // Message flash d'erreur si l'email est invalide
            $this->addFlash('error', 'Veuillez entrer une adresse email valide.');
        }
    
        // Rediriger l'utilisateur vers la même page ou une autre
        return $this->redirectToRoute('app_reset');
    }
}