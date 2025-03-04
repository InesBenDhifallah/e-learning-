<?php
// src/Form/CommentType.php
namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CommentType extends AbstractType
{
    // Construire le formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Votre commentaire',  // L'étiquette du champ
                'attr' => [
                    'rows' => 4,
                    'placeholder' => 'Écrivez votre commentaire ici...',
                    'minlength' => 2,
                    'maxlength' => 1000,
                    'class' => 'comment-input'
                ],
                'required' => true,  // Le champ est requis
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le commentaire ne peut pas être vide'
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 1000,
                        'minMessage' => 'Le commentaire doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Le commentaire ne peut pas dépasser {{ limit }} caractères'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z0-9\s\-_.,!?\'\"À-ÿ]+$/',
                        'message' => 'Le commentaire ne peut contenir que des lettres, chiffres et ponctuations basiques'
                    ])
                ]
            ]);
    }

    // Configurer les options du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,  // Lier ce formulaire à l'entité Comment
        ]);
    }
}
