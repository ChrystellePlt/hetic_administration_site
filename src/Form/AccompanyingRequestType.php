<?php

namespace App\Form;

use App\Entity\AccompanyingRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccompanyingRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, ['label' => 'Prénom'])
            ->add('lastName', TextType::class, ['label' => 'Nom de famille'])
            ->add('email', EmailType::class, ['label' => 'Addresse e-mail'])
            ->add('wantedPromotion', TextType::class, ['label' => 'Promotion demandée'])
            ->add('actualSchoolLevel', TextType::class, ['label' => 'Niveau d\'études actuel'])
            ->add('twitter', TextType::class, ['label' => 'Twitter'])
            ->add('facebook', TextType::class, ['label' => 'Facebook'])
            ->add('phoneNumber', TextType::class, ['label' => 'Téléphone (+33)'])
            ->add('wantedSpeciality', TextType::class, ['label' => 'Spécialité recherchée'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AccompanyingRequest::class,
        ]);
    }
}
