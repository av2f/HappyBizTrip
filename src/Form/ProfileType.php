<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\DateStringToDateTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ProfileType extends AbstractType
{
    private $transformer;

    public function __construct(DateStringToDateTransformer $transformer) {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender')
            ->add('pseudo', TextType::class, [
                'help' => 'form.help_pseudo',
                'attr' => [
                    'placeholder' => 'form.placeholder_pseudo'
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => 'form.label_firstname',
                'attr' => ['placeholder' => 'form.placeholder_firstname']
            ])
            ->add('lastName', TextType::class, [
                'label' => 'form.label_lastname',
                'attr' => ['placeholder' => 'form.placeholder_lastname']
            ])
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => 'form.placeholder_email']
            ])
            ->add('birthDate', TextType::class, [
                'help' => 'form.help_birthDate',
                'attr' => ['class' => 'js-datepicker'],
                'help_attr' => ['class' => 'form-date-help'],
            ])
            ->add('password', PasswordType::class, [
                'help' => 'form.help_password',
                'attr' => ['placeholder' => 'form.placeholder_password']
            ])
            
            ->add('firstName')
            ->add('lastName')
            ->add('situation')
            ->add('avatar')
            ->add('profession')
            ->add('description')
        ;

        // manage date format
        $builder->get('birthDate')
            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
