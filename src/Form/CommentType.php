<?php
// src/Form/CommentType.php
namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    // Construire le formulaire
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'label' => 'Votre commentaire',  // L'étiquette du champ
                'attr' => [
                    'placeholder' => 'Écrivez votre commentaire ici...',  // Un texte de remplacement pour guider l'utilisateur
                    'rows' => 5,  // Définir une hauteur pour la zone de texte
                ],
                'required' => true,  // Le champ est requis
            ]);
    }

    // Configurer les options du formulaire
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,  // Lier ce formulaire à l'entité Comment
        ]);
    }
}
