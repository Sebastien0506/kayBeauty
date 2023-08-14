<?php

namespace App\Form;

use App\Entity\Prestation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PrestationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',null, [
               'label' => 'Nom de la prestation',
               'label_attr' => [
                'class' => 'text-success'
               ],
               'required' => false,
               'attr' => [
                'placeholder' => 'Saisir le nom de la prestation',
                'class' => 'border border-warning',
               ],
               'help' => 'Le nom de la la prestation doit contenir entre 5 et 50 caractères',
               'help_attr' => [
                'class' => "text-info"
               ],
               'constraints' => [
                new NotBlank([
                    'message' => 'Veuillez saisir le nom de la prestation'
                ]),
                new Length([
                    'min' => 5,
                    'max' => 50,
                    'minMessage' => 'Veuillez saisir un nom d\'au moin 5 caractères',
                    'maxMessage' => 'Veuillez saisir un nom de moin de 50 caractères'
                ])
               ]
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix de la prestation',
                'currency' => 'euro',
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le prix de la prestation'
                    ]),
                    new Positive([
                        'message' => 'Veuillez saisir un nombre  supérieur a zéro'
                    ])
                ]
            ])
            ->add('dureMinutes',null , [
                'label' => 'Duré(en minue)'
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image de la prestation',
                'label_attr' => [
                    'class' => 'form-labe mt4'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez selectionnez une image'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'row' => 6
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir la description de la prestation'
                    ]),

                    new Length([
                        'min' => 10,
                        'max' => 400,
                        'minMessage' => 'Veuillez saisir un message d\'au moin 10 caractères',
                        'maxMessage' => 'Veuillez saisir une description de moin de 400 caractères'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prestation::class,
        ]);
    }
}
