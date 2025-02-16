<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\ParentsignupType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ParentsignupController extends AbstractController
{
    #[Route('/parentsignup', name: 'app_parentsignup')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Create a new User entity
        $user = new User();

        // Create the form
        $form = $this->createForm(ParentsignupType::class, $user);

        // Handle the form submission
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the password
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            // Set the roles and isActive
            $user->setRoles(['ROLE_Parent']);
            $user->setIsActive(false); // Set isActive to false

            // Save the user to the database
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect to a success page or login page
            return $this->redirectToRoute('app_abonnement_index'); // Change 'app_login' to your login route
        }

        // Render the form
        return $this->render('parentsignup/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}