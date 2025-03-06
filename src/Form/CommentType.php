<?php
// src/Form/CommentType.php
namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType
{
    // Construire le formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Commentaire',
                'attr' => [
                    'rows' => 5,
                    'class' => 'form-control'
                ],
                'error_bubbling' => false,
                'invalid_message' => 'Le contenu du commentaire n\'est pas valide'
            ])
        ;
    }

    // Configurer les options du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,  // Lier ce formulaire à l'entité Comment
            'attr' => ['novalidate' => 'novalidate'], // Désactive la validation HTML5
            'validation_groups' => ['Default'], // Active la validation par défaut
        ]);
    }
}
