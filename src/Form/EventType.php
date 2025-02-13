<?php
namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('time', TextType::class, [
                'label' => 'Time (HH:MM)',
                'attr' => ['placeholder' => 'e.g., 14:30']
            ])
            ->add('place')
            ->add('category')
            ->add('speaker')
            ->add('image', FileType::class, [
                'required' => false, // Optional
                'mapped' => false,   // If you're not saving the file directly to the entity
            ])
            //->add('created_by', EntityType::class, [
              //  'class' => User::class,  // Make sure to set the appropriate entity
              //  'choice_label' => 'username',  // Assuming you have a 'username' field in the User entity
           // ])
           ->add('created_by')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
