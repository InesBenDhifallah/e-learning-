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
use Symfony\Component\Form\Extension\Core\Type\DateType;



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
        
->add('date_expiration', DateType::class, [
    'widget' => 'single_text',
    'required' => false, // Permet d'accepter un champ vide
    'input' => 'datetime', // Assure que la valeur sera bien un objet DateTime
])
        ->add('cvv');
       
        
        ;
}


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paiement::class,
        ]);
    }
}
