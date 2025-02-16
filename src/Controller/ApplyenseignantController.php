<?php
// src/Controller/ApplyenseignantController.php
namespace App\Controller;

use App\Entity\User;
use App\Form\EnseignantformType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApplyenseignantController extends AbstractController
{
    /**
     * @Route("/apply-enseignant", name="apply_enseignant")
     */
    public function apply(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        
        // Create and handle the form
        $form = $this->createForm(EnseignantformType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hash the password
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            
            // Assign the role (e.g., ROLE_TEACHER)
            $user->setRoles(['ROLE_TEACHER']); // Set the teacher role

            // Persist the user
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect to some page or show success
            return $this->redirectToRoute('success');
        }

        return $this->render('applyenseignant/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
