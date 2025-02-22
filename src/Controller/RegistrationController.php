<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Module;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Repository\UserRepository;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
<<<<<<< HEAD
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): Response {
=======
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
>>>>>>> ba8df4d (integration b-f)
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

<<<<<<< HEAD
        if ($form->isSubmitted()) {
            $existingUser = $userRepository->findOneBy(['email' => $user->getEmail()]);
            if ($existingUser) {
                $form->get('email')->addError(new \Symfony\Component\Form\FormError('This email is already registered.'));
            }

            if ($form->isValid()) {
                $plainPassword = $form->get('plainPassword')->getData();
                $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

                $user->setRoles(['ROLE_TEACHER']);
                $matiereId = $form->get('matiere')->getData();
                $matiere = $entityManager->getRepository(Module::class)->find($matiereId);

                if (!$matiere) {
                    throw new \Exception("Module not found.");
                }

                $user->setIdmatiere($matiere);
                $entityManager->persist($user);
                $entityManager->flush();

                $this->emailVerifier->sendEmailConfirmation(
                    'app_verify_email',
                    $user,
                    (new TemplatedEmail())
                        ->from(new Address('aziz.bellagha@gmail.com', 'Alpha Education Mail Bot'))
                        ->to((string) $user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );

                return $this->redirectToRoute('app_login');
            }
=======
        if ($form->isSubmitted() && $form->isValid()) {
            // Encode le mot de passe
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Définit le rôle par défaut
            $user->setRoles(['ROLE_USER']);
            
            // Persiste l'utilisateur
            $entityManager->persist($user);
            $entityManager->flush();

            // Ajoute un message flash
            $this->addFlash('success', 'Votre compte a été créé avec succès !');

            // Redirige vers la page de connexion
            return $this->redirectToRoute('app_login');
>>>>>>> ba8df4d (integration b-f)
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        try {
            /** @var User $user */
            $user = $this->getUser();
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (\Exception $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getMessage(), [], 'VerifyEmailBundle'));
            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');
        return $this->redirectToRoute('app_register');
    }
}
