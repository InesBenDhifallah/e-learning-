<?php

namespace App\Form;

use App\Entity\Participation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbrTickets', IntegerType::class, [
                'label' => 'Number of Tickets',
                'required' => true
            ])
            ->add('somme', MoneyType::class, [
                'label' => 'Total Amount',
                'currency' => 'EUR',
                'required' => true
            ])
            ->add('paymentMethod', ChoiceType::class, [
                'label' => 'Payment Method',
                'choices' => [
                    'PayPal' => 'paypal',
                    'Stripe' => 'stripe'
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Confirm Participation'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participation::class,
        ]);
    }
}