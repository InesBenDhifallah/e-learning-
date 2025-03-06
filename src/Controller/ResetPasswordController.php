<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface; 

class ResetPasswordController extends AbstractController
{
    #[Route('/reset-password', name: 'app_reset_password')]
    public function resetPassword(Request $request): Response
    {
        // Get the email from the query parameter
        $email = $request->query->get('email');

        // Render the reset password form template and pass the email
        return $this->render('reset/reset_password.html.twig', [
            'email' => $email,
        ]);
    }

    #[Route('/update-password', name: 'app_update_password', methods: ['POST'])]
    public function updatePassword(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Get the email and new password from the form
        $email = $request->request->get('email');
        $newPassword = $request->request->get('password');
    
        // Find the user by email
        $user = $userRepository->findOneBy(['email' => $email]);
    
        if ($user) {
            // Hash the new password using the PasswordHasherInterface
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
    
            // Set the new hashed password to the user
            $user->setPassword($hashedPassword);
    
            // Use the EntityManager to persist and flush the updated user entity
            $entityManager->persist($user);  // Persist the changes
            $entityManager->flush();         // Save changes to the database
    
            // Add a success message
            $this->addFlash('success', 'Your password has been updated successfully.');
        } else {
            // Add an error message if the user is not found
            $this->addFlash('error', 'User not found.');
        }
    
        // Redirect to the login page or another appropriate page
        return $this->redirectToRoute('app_login');
    }
}