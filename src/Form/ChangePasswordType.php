<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true,
                'label' => 'Mon email'
            ])

            ->add('FirstName', TextType::class, [
                'disabled' => true,
                'label' => 'Mon Préom'
            ])
            ->add('LastName', TextType::class, [
                'disabled' => true,
                'label' => 'Mon Nom'
            ])
            ->add('ancienne_password', PasswordType::class, [
                'label' => 'Mon mot de passe actuel',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Saisir votre mot de passe actuel'
                ]
            ])

            ->add('Nouveau_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'le mot de passe et la confirmation doivent etre identique. ',
                'label' => 'votre nouveau mot de passe',
                'required' => true,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Nouveau mot de pass',
                    'attr' => [
                        'placeholder' => 'Insérer votre nouveau mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'Comfirmez votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmez votre nouveau mot de passe'
                    ]
                ]
            ])
            ->add('Submit', SubmitType::class, [
                'label' => 'Mettre à jour'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
