<?php

namespace App\Form;

use App\Entity\Article;
use App\Form\PanierType;
use App\Form\ProduitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('produit', ProduitType::class, [
                'label' => 'Nom du produit',
                'attr' => [],
                'prix' => []
            ])
            ->add('panier',PanierType::class, [
                'label' => "Nom de l'article"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
