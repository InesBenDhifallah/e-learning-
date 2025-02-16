<?php

namespace App\Form;

use App\Entity\Abonnement;
use App\Entity\Paiement;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaiementType extends AbstractType
{
  

public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('nom')
        ->add('email')
        ->add('type_carte', ChoiceType::class, [
            'choices' => [
                'Visa' => 'Visa',
                'MasterCard' => 'MasterCard',
            ],
            'expanded' => false, // false = liste déroulante, true = boutons radio
            'multiple' => false, // Permet de choisir une seule option
            'placeholder' => 'Sélectionnez un type de carte',
        ])
        ->add('num_carte')
        ->add('date_expiration', null, [
            'widget' => 'single_text',
        ])
        ->add('cvv')
       
        
        ;
}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paiement::class,
        ]);
    }
}
