<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\DateStringToDateTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProfileType extends AbstractType
{
    private $transformer;

    public function __construct(DateStringToDateTransformer $transformer) {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', ChoiceType::class, [
                'label' => 'form.label_gender',
                'label_attr' => ['class' => 'label-profile'],
                'required' => false,
                'choices'  => [
                    'form.choice_woman' => 'W',
                    'form.choice_man' => 'M'
                ],
            ])
            ->add('situation', ChoiceType::class, [
                'label' => 'form.label_situation',
                'label_attr' => ['class' => 'label-profile'],
                'required' => false,
                'choices'  => [
                    'form.choice_single' => 'S',
                    'form.choice_couple' => 'C',
                    'form.choice_keep' => 'K'
                ],
            ])
            ->add('pseudo', TextType::class, [
                'help' => 'form.help_pseudo',
                'attr' => [
                    'class' => 'form-control-sm',
                    'placeholder' => 'form.placeholder_pseudo'
                ]
            ])
            ->add('firstName', TextType::class, [
                'label' => 'form.label_firstname',
                'label_attr' => ['class' => 'label-profile'],
                'attr' => [
                    'class' => 'form-control-sm',
                    'placeholder' => 'form.placeholder_firstname'
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'form.label_lastname',
                'label_attr' => ['class' => 'label-profile'],
                'attr' => [
                    'class' => 'form-control-sm',
                    'placeholder' => 'form.placeholder_lastname'
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control-sm',
                    'placeholder' => 'form.placeholder_email'
                ]
            ])
            ->add('birthDate', TextType::class, [
                'help' => 'form.help_birthDate',
                'attr' => ['class' => 'js-datepicker form-control-sm'],
                'help_attr' => ['class' => 'form-date-help'],
            ])
            ->add('profession', TextType::class, [
                'label' => 'form.label_profession',
                'label_attr' => ['class' => 'label-profile'],
                'attr' => [
                    'class' => 'form-control-sm',
                    'placeholder' => 'form.placeholder_profession'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'form.label_company',
                'label_attr' => ['class' => 'label-profile'],
                'attr' => [
                    'class' => 'form-control-sm',
                    'placeholder' => 'form.placeholder_company'
                ]
            ])
            ->add('description', CKEditorType::class)
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
