<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentModificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('firstName')
            ->add('lastName')
            ->add('promotion')
            ->add('twitter')
            ->add('facebook')
            ->add('phoneNumber')
            ->add('skills')
            ->add('password')
            ->add('confirmationToken')
            ->add('email')
            ->add('personnalEmail')
            ->add('isActive')
            ->add('roles')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
