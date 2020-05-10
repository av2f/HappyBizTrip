<?php

namespace App\Form;

use App\Entity\User;
use App\Form\DataTransformer\DateStringToDateTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class HomeRegisterType extends AbstractType
{
    private $transformer;

    public function __construct(DateStringToDateTransformer $transformer) {
        $this->transformer = $transformer;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                'help' => 'form.help_pseudo',
                'attr' => [
                    'placeholder' => 'form.placeholder_pseudo'
                ]
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
