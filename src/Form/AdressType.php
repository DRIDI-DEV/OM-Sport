<?php

namespace App\Form;

use App\Entity\Adress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Mettez un nom pour votre adresse',
                'attr' => [
                    'placeholder' => 'Nommez votre adresse'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Entrez votre prénom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Votre Nom',
                'attr' => [
                    'placeholder' => 'Entrez votre nom'
                ]
            ])
            ->add('company', TextType::class, [
                'label' => 'Votre entreprise',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Entrez le nom de votre entreprise (Facultatif)'
                ]
            ])
            ->add('adress', TextType::class, [
                'label' => 'Votre adresse',
                'attr' => [
                    'placeholder' => 'Exp :  8 Rue de Tunis ...'
                ]
            ])
            ->add('codepostal', TextType::class, [
                'label' => 'Votre code postal',
                'attr' => [
                    'placeholder' => 'Entrer votre code postal'
                ]
            ])
            ->add('commune', TextType::class, [
                'label' => 'Votre ville',
                'attr' => [
                    'placeholder' => 'Entrez votre ville'
                ]
            ])
            ->add('pays', CountryType::class, [
                'label' => 'votre pays',
                'attr' => [
                    'placeholder' => 'Entrez votre pays'
                ]
            ])
            ->add('telephone', TelType::class, [
                'label' => 'Entrez votre numéro de téléphone',
                'attr' => [
                    'placeholder' => 'Votre numéro de téléphone'
                ]
            ])
            ->add('user', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
