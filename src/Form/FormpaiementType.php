<?php

namespace App\Form;

use App\Entity\Abonnement;
use App\Entity\Paiement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormpaiementType extends AbstractType
{
     public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'attr' => ['class' => 'mt-2 w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lime-500'],
            ])
            ->add('email', null, [
                'attr' => ['class' => 'mt-2 w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lime-500'],
            ])
            ->add('type_carte', ChoiceType::class, [
                'choices' => [
                    'Choisir le type de carte' => null,
                    'Visa' => 'Visa',
                    'MasterCard' => 'Mastercard',
                ],
                'expanded' => false, 
                'multiple' => false, 
                'attr' => ['class' => 'mt-2 w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lime-500'],
            ])
            ->add('num_carte', null, [
                'attr' => ['class' => 'mt-2 w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lime-500'],
            ])
            ->add('date_expiration', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'mt-2 w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lime-500'],
            ])
            ->add('cvv', null, [
                'attr' => ['class' => 'mt-2 w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lime-500',
            'placeholder' => '123'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Procéder au paiement',
                'attr' => [
                    'class' => 'w-full py-3 bg-lime-500 text-white font-medium rounded-lg hover:bg-lime-600 transition duration-300'
                ],
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paiement::class,
        ]);
    }
}
