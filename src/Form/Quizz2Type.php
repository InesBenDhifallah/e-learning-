<?php

namespace App\Form;

use App\Entity\Quizz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Quizz2Type extends AbstractType // ✅ Corrige ici !
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matiere', TextType::class)
            ->add('chapitre', TextType::class)
            ->add('difficulte', IntegerType::class)
            ->add('bestg', TextType::class, [
                'required' => false,
                'label' => 'Best'
            ])
            ->add('etat', TextType::class, [
                'required' => false
            ])
            ->add('gain', TextType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quizz::class,
        ]);
    }

}
