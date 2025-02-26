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
use Symfony\Component\Form\FormError;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if email is already registered
            if ($userRepository->findOneBy(['email' => $user->getEmail()])) {
                $form->get('email')->addError(new FormError('Cette adresse e-mail est déjà enregistrée.'));
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                ]);
            }

            // Hash password securely
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($hashedPassword);
            }

            // Assign default role
            $user->setRoles(['ROLE_TEACHER']);

            // Handle 'matiere' field properly
            $matiereId = $form->get('matiere')->getData();
            if ($matiereId) {
                $matiere = $entityManager->getRepository(Module::class)->find($matiereId);
                if (!$matiere) {
                    $form->get('matiere')->addError(new FormError("Matière non trouvée."));
                    return $this->render('registration/register.html.twig', [
                        'registrationForm' => $form->createView(),
                    ]);
                }
                $user->setIdmatiere($matiere);
            }

            // Save user
            $entityManager->persist($user);
            $entityManager->flush();

            // Send email verification
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address('aziz.bellagha@gmail.com', 'Alpha Education Mail Bot'))
                    ->to((string) $user->getEmail())
                    ->subject('Veuillez confirmer votre e-mail')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            $this->addFlash('success', 'Votre compte a été créé avec succès. Veuillez vérifier votre e-mail.');

            return $this->redirectToRoute('app_login');
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
            $this->addFlash('danger', $translator->trans($exception->getMessage(), [], 'VerifyEmailBundle'));
            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Votre adresse e-mail a été vérifiée.');
        return $this->redirectToRoute('app_login');
    }
}
