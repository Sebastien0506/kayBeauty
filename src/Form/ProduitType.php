<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', null, [
                'label' => 'Titre du produit',
                'label_attr' => [
                    'class' => 'text-success'
                ],
                'required' => false,
                'attr' => [
                    'placeholder' =>'Saisir le titre du produit',
                'class' => 'border border-warning',
                ],
                'help' => ' Le titre du produit doit être composé entre 5 et 40 caractères',
                'help_attr' => [
                    "text-info"
                ],
                'constraints' => [
                    new NotBlank ([
                        'message' => 'Veuillez saisir le titre du produit'
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 40,
                        'minMessage' => 'Veuillez saisir un titre d\'au moin 5 caractères',
                        'maxMessage' => 'Veuillez saisir un titre de moin de 40 caractères',

                    ])
                ]
            ])
            ->add('prix', MoneyType::class, [
                'label' => 'Prix du produit',
                'currency' => 'euro',
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir le prix du produit'
                    ]),
                    new Positive([
                        'message' => 'Veuillez saisir un nombre supérieur a zéro'
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'text-success'
                ],
                'required' => false,
                'attr' => [
                    'placeholder' => 'Saisir la description du produit',
                    'class' => 'border border-warning',
                ],
                'help' => 'La description doit être entre 10 et 400 caractères',
                'help_attr' =>[
                    "text-info",
                ],
                'constraints' => [
                    new NotBlank([
                      'message' => 'Veuillez saisir la description du produit'  
                    ]),
                    new Length([
                        'min' => 10,
                        'max' => 400,
                        'minMessage' => 'veuillez saisir une description d\'au moin 10 caractères',
                        'maxMessage' => 'Veuillez saisir une description de moin de 400 caractères'
                    ]),
                    
                ]

            ])
            // On ajoute le champ "image" dans le formulaire
            //Il n'est pas lié a la base de donnée (mapped à false)
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image du produit',
                'label_attr' => [
                    'class' => 'form-labe mt4'
                ]
                
            ])

            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'expanded' => true,
                'multiple' => true,
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une catégorie'
                    ])
                ],
                'placeholder' => 'Veuillez saisir une catégorie'


            ])

            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
