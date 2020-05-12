<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo')
            ->add('email')
            ->add('password')
            ->add('gender')
            ->add('firstName')
            ->add('lastName')
            ->add('birthDate')
            ->add('situation')
            ->add('avatar')
            ->add('profession')
            ->add('description')
            ->add('isSubscribed')
            ->add('isActive')
            ->add('completed')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
