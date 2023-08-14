<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           ->add('email')
           ->add('roles', ChoiceType::class, [
            'label' => 'Role',
            'choices' => [
                'utilisateur' => 'ROLE_USER',
                'Administrateur' => 'ROLE_ADMIN'
            ],
            'mapped' => false,
           ])



            ->add('email')
            ->add('roles')
            ->add('password')
            ->add('nom')
            ->add('prenom')
            ->add('adresse', CollectionType::class, [
                'entry_type' => AdresseType::class, 
                'label' => false,
                'required' => false,
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
            
                
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
