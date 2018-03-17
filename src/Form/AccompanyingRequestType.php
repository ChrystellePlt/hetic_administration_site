<?php

namespace App\Form;

use App\Entity\AccompanyingRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
            ->add('date', DateType::class, [
                'widget' => 'choice',
                'format' => 'ddMMyyyy',
            ])
            ->add('wantedPromotion', ChoiceType::class, [
                'choices' => [
                    'WEB1' => 'WEB1',
                    'WEB2' => 'WEB2',
                    'WEB3' => 'WEB3',
                    'WM1' => 'WM1',
                    'WM2' => 'WM2',
                    'WM3' => 'WM3',
                    '3D1' => '3D1',
                    '3D2' => '3D2',
                    '3D3' => '3D3',
                ],
                'label' => 'Promotion demandée',
            ])
            ->add('actualSchoolLevel', ChoiceType::class, [
                'choices' => [
                    'BAC' => 'BAC',
                    'BAC + 1' => 'BAC + 1',
                    'BAC + 2' => 'BAC + 2',
                    'BAC + 3' => 'BAC + 3',
                    'BAC + 4' => 'BAC + 4',
                    'BAC + 5' => 'BAC + 5',
                ],
                'label' => 'Niveau d\'études actuel',
            ])
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
