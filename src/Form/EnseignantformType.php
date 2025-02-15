<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnseignantformType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom complet'])
            ->add('phonenumber', TextType::class, ['label' => 'Téléphone'])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Password',
                'mapped' => false, // This field is not mapped to the entity
            ])
            ->add('matiere', ChoiceType::class, [
                'label' => 'Matière enseignée',
                'choices' => [
                    'Mathématiques' => 'mathematiques',
                    'Sciences' => 'sciences',
                    'Physique' => 'physique',
                    'Art' => 'art',
                    'Informatique' => 'informatique',
                    'Français' => 'francais',
                    'Anglais' => 'anglais',
                ],
                'placeholder' => 'Choisissez une matière',
            ])
            ->add('experience', NumberType::class, ['label' => 'Expérience (années)'])
            ->add('reason', TextareaType::class, ['label' => 'Pourquoi voulez-vous rejoindre Alpha Education ?']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}