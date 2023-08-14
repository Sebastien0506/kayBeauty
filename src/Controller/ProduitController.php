<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Article;
use App\Entity\Produit;

use App\Entity\Categorie;

use App\Form\ProduitType;
use App\Repository\ArticleRepository;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class ProduitController extends AbstractController
{

   /**
    * @Route("/gestion", name="gestion_produit")
    */

    public function gestion_produit(ProduitRepository $produitRepository):Response
    {
      $produits = $produitRepository->findAll();
      
      return $this->render('produit/gestion_produit.html.twig', [
        'produits' => $produits
      ]);

    }


  /**
   * @Route("/ajouter", name="produit_ajouter")
   */
  public function produit_ajouter(Request $request, ProduitRepository $produitRepository):Response
  {//sers a ajouté des produits
    $produit = new Produit();
    
    $form = $this->createForm(ProduitType::class, $produit);

    $form->handleRequest($request);
    
  

    if($form->isSubmitted() && $form->isValid())
    {
       
      
        $produitRepository->save($produit, true);

        $this->addFlash('success','Le produit a bien été ajouté');

        return $this->redirectToRoute('produit_ajouter');
    }
    return $this->render('produit/produit_ajouter.html.twig', [
        'formProduit' => $form->createView()
    ]);
  }





 /**
  * @Route("/modifier/{id}", name="produit_modifier")
  */
  public function produit_modifier(Produit $produit, Request $request, ProduitRepository $produitRepository):Response
  {
   $form = $this->createForm(ProduitType::class, $produit);

   $form->handleRequest($request);

   if($form->isSubmitted() AND $form->isValid())
   {
    $produitRepository->save($produit, true);

    $this->addFlash('success','Le produit a bien été modifier');

    return $this->redirectToRoute('gestion_produit');
   }

    return $this->render('produit/produit_modifier.html.twig', [
      'formProduit' => $form->createView()
    ]);
  }

/**
 * @Route("/produit/supprimer/{id}", name="produit_supprimer")
 */
public function produit_supprimez(Produit $produit, ProduitRepository $produitRepository, Article $article, ArticleRepository $articleRepository)
{
  //  $produit = $article->getProduit();
   
  //  if(!empty($produit)){
  //   foreach($produit as $produit){
  //     $produit->removeArticle($article);
  //     $entityManager->remove($article);
  //   }
  //   $entityManager->flush();
  //  }
  //  $entityManager->remove($produit);
  //  $entityManager->flush();
   $articleRepository->remove($article, true);
   $produitRepository->remove($produit, true);
  
  $this->addFlash('success', 'Le produit a bien été supprimer');

  return $this->redirectToRoute('gestion_produit');
}

/**
 * @Route("/gestion/categorie", name="gestion_categorie")
 */
public function gestion_categorie(CategorieRepository $categorieRepository):Response
{
  $categorie = $categorieRepository->findAll();

  return $this->render('categorie/gestion_categorie.html.twig', [
    'categorie' => $categorie
  ]);
}

/**
 * @Route("/categorie/supprimer/{id}", name="categorie_supprimer")
 */
public function categorie_supprimer(Categorie $categorie, CategorieRepository $categorieRepository)
{
  $categorieRepository->remove($categorie, true);

  $this->addFlash('success','La categorie a bien été supprimer');

  return $this->redirectToRoute('gestion_categorie');
}

}
