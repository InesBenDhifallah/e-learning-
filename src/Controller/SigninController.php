<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

final class SigninController extends AbstractController
{
    #[Route('/signin', name: 'app_signin')]
    public function index(AuthenticationUtils $authenticationUtils, Request $request): Response
    {
        // Get login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        // Create a basic login form manually
        $loginForm = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => 'Entrez votre email'],
                'data' => $lastUsername
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['placeholder' => 'Entrez votre mot de passe']
            ])
            ->add('_token', HiddenType::class, [
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Se connecter',
                'attr' => ['class' => 'login-btn']
            ])
            ->getForm();

        return $this->render('signin/signin.html.twig', [
            'loginForm' => $loginForm->createView(), // Pass the form to Twig
            'error' => $error,
        ]);
    }
}
