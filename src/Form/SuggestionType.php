<?php

namespace App\Form;

use App\Entity\Suggestion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SuggestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu', TextType::class, [
                'label' => 'Suggestion',
                'attr' => [
                    'class' => 'w-full p-2 border border-gray-300 rounded-lg'
                ]
            ])
            ->add('estCorrecte', CheckboxType::class, [
                'label' => 'Est-ce la bonne réponse ?',
                'required' => false,
                'attr' => [
                    'class' => 'mr-2'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Suggestion::class,
        ]);
    }
}
