<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message', null, [
                'label' => false,
                'attr' => [
                    'row' => 6,
                    'placeholder' => 'Laissez votre avis'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir votre message'
                    ]),
                    new Length([
                        'max' => 200,
                        'maxMessage' => 'Veuillez saisir un commentaire de moin de 200 caractères'
                    ])
                ]
             ])

            
           
                
            
            // ->add('createdAt')
            // ->add('produit')
            // ->add('user')
        ;
    }

    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
