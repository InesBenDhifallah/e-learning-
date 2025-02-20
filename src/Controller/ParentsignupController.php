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
        $user = new User();
        $form = $this->createForm(ParentsignupType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_Parent']);
            $user->setIsActive(false);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_abonnement_index'); 
        }
        return $this->render('parentsignup/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}