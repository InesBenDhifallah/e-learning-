<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'attr' => ['placeholder' => 'Nom de l\'événement']
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Description de l\'événement']
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type d\'événement',
                'choices' => [
                    'En ligne' => 'enligne',
                    'Présentiel' => 'presentiel'
                ],
                'expanded' => false,
                'multiple' => false,
                'attr' => [
                    'id' => 'event-type', // Explicitly setting the ID
                    'data-event-type' => 'true' // Extra attribute for JavaScript
                ]
            ])
            ->add('localisation', TextType::class, [
                'label' => 'Localisation',
                'required' => false, // Should NOT be required when type is "enligne"
                'attr' => [
                    'placeholder' => 'Lieu de l\'événement',
                    'data-localisation' => 'true' // Help JavaScript find it
                ]
            ])
            ->add('dateDebut', DateType::class, [
                'label' => 'Date de début',
                'widget' => 'single_text'
            ])
            ->add('dateFin', DateType::class, [
                'label' => 'Date de fin',
                'widget' => 'single_text'
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix',
                'attr' => ['placeholder' => 'Prix en €']
            ])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
