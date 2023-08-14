<?php

namespace App\Controller;


use App\Entity\Produit;
use App\Entity\Prestation;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use App\Repository\PrestationRepository;
use App\Repository\CommentaireRepository;
use App\Repository\LogoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{
/**
 * @Route("home", name="home")
 */
public function home(PrestationRepository $prestationRepository, LogoRepository $logoRepository):Response
{
    $logo = $logoRepository->findAll();
    $prestation = $prestationRepository->findAll();
    return $this->render('front/home.html.twig', ['prestation' => $prestation, 'logo' => $logo]);
}


/**
 * @Route("inscription", name="inscription")
 */
public function inscription()
{
    return $this->render('inscription');
}


/**
 * @Route("/produit", name="produit")
 */
public function produit(ProduitRepository $produitRepository):Response
{  //sers a affiche tous les produit 
    $produits = $produitRepository->findAll();
    

    return $this->render('front/produit.html.twig', [
        'produits' => $produits
    ]);

}

/**
 * @Route("/fiche/produit/{id}", name="fiche_produit")
 */
public function fiche_produit(Produit $produit,CommentaireRepository $commentaireRepository, Request $request) 
{
  
  //commentaire utilisateur 
  $commentaires = $commentaireRepository->findBy(['produit' => $produit]);
  
  $user = $this->getUser();

  $commentaire = new Commentaire();

  $form = $this->createForm(CommentaireType::class, $commentaire);

  $form->handleRequest($request);

  // Traitement du formulaire 
  if($form->isSubmitted() AND $form->isValid())
  {
    $commentaire->setUser($user);
    $commentaire->setProduit($produit);
    $commentaire->setCreatedAt(new \DateTimeImmutable('now'));
    $commentaireRepository->save($commentaire, true);
    return $this->redirectToRoute('fiche_produit', ['id' => $produit->getId()] );
  }

  

  return $this->render('front/fiche_produit.html.twig', [
    'produit' => $produit,
    'formCommentaire' => $form->createView(),
    'commentaires' => $commentaires
  ]); 
  
}

/**
    * @Route("prestation", name="prestation")
    */
    public function prestation(PrestationRepository $prestationRepository):Response
    {
        $prestation = $prestationRepository->findAll();

        return $this->render('front/prestation.html.twig', ['prestation' => $prestation
    ]);
    }

/**
 * @Route("/fiche/prestation/{id}", name="fiche_prestation")
 */
public function fiche_prestation(Prestation $prestation, CommentaireRepository $commentaireRepository, Request $request)
{ 

$commentaires = $commentaireRepository->findBy(['prestation' => $prestation]);

$user =$this->getUser();

$commentaire = new Commentaire();

$form = $this->createForm(CommentaireType::class, $commentaire);

$form->handleRequest($request);

if($form->isSubmitted() AND $form->isValid())
{
  $commentaire->setUser($user);
  $commentaire->setPrestation($prestation);
  $commentaire->setCreatedAt(new \DateTimeImmutable('now'));
  $commentaireRepository->save($commentaire, true);
  $this->addflash('message', 'Votre commentaire a bien été envoyer');
  return $this->redirectToRoute('fiche_prestation', ['id' => $prestation->getId()] );
}

return $this->render('prestation/fiche_prestation.html.twig', [
    'prestation' => $prestation,
    'formCommentaire' => $form->createView(),
    'commentaires' => $commentaires 
]);

}
  
   /**
 * @Route("/categorie/{id}", name="app_categorie")
 */
public function categorie( $id, CategorieRepository $categorieRepository):Response
                           
{
//sers a afficher les produits par catégorie
  $categorie = $categorieRepository->find($id);
 
 

  return $this->render('front/produits.html.twig', [
    'categorie' => $categorie
  ]);
}

/**
 * @Route("/rendez_vous", name="rendez_vous")
 */
public function rendez_vous()
{
  return $this->render('front/rendez_vous.html.twig');
}



  

}

  
