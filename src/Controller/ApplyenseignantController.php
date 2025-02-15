<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EnseignantformType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; // Add this line
use Symfony\Component\Routing\Attribute\Route;

final class ApplyenseignantController extends AbstractController
{
    #[Route('/applyenseignant', name: 'app_applyenseignant')]
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher // Inject the password hasher
    ): Response {
        $user = new User();
        $form = $this->createForm(EnseignantformType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the plain password and set it on the User entity
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData() // Get the plain password from the form
                )
            );

            // Assign the role (e.g., ROLE_TEACHER)
            $user->setRoles(['ROLE_TEACHER']);

            // Persist the User entity to the database
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect after successful submission
            return $this->redirectToRoute('app_applyenseignant');
        }

        return $this->render('applyenseignant/applyenseignant.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
