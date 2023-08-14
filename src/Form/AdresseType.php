<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numeroDeRue',null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le numero de la rue'
                    ])
                ]
            ])
            ->add('nomDeLaRue',null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le nom de la rue'
                    ])
                ] 
            ])
            ->add('codePostal', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le code Postal'
                    ])
                ]
            ])
            ->add('ville', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => ' Veuillez saisir le nom de la ville'
                    ])
                ]
            ])

            ->add('numeroDeTelephone', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un numero de telephone'
                    ])
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
