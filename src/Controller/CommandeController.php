<?php

namespace App\Controller;

use DateTime;
use App\Entity\Adresse;
use App\Entity\Article;
use App\Entity\Commande;
use App\Form\AdresseType;
use Symfony\Component\Uid\Ulid;
use App\Repository\AdresseRepository;
use App\Repository\ArticleRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index( ArticleRepository $articleRepository, ProduitRepository $produitRepository, SessionInterface $session, CommandeRepository $commandeRepository, EntityManagerInterface $em): Response
    { 

        //Permet de validez la commande et de passer a l'étape pour enregistrer ces information personnel 
        
        $ulid = new Ulid();
        $numeroDeCommande = 'CMD-' . strtoupper(substr($ulid->toBase32(), 0, 8));//Ce code est responsable de la création du numero de commande a partir de l'ulid générer
        //'CMD-' est une chaine de cararctère qui représente un préfixe que l'on peut utiliser pour identifier les numero de commande 
        //'$ulid->toBas32() est une méthode de l'objet ULID qui retourne la représentation de l'ULID en base 32. La base 32 est un systeme de numération utilisant les caractères de A a Z et les chiffres de 2 a 7
        //'substr($ulid->toBase32(), 0, 8)' est une fonction qui extrait les 8 premiers caractères de la représentation en base 32 de l'ULID 
        //'strtoupper()'est une fonction qui convertit tous les caractères de la chaine en majuscules.
        $dateDeCommande = new DateTime();
        $user = $this->getUser();
        $commande = new Commande();
        $commande->setUser($user);
        $panierSession = $session->get("panier");

        //On calcule le total de la commande
        $total = 0;
        $commande->setNumeroDeCommande($numeroDeCommande);
        $commande->setDateDeCommande($dateDeCommande); 
        $commandeRepository->save($commande, false);
       
        foreach($panierSession as $key=>$value){//On fait une boucle sur chaque produit du panier
            
            $article = new Article();//On définit les propriété de l'instance Article en utilisant les données récupérées

            $produit = $produitRepository->find($key);
            $total += $produit->getPrix() * $value;
            
            $article->setCommande($commande);
            $article->setProduit($produit);
            $article->setQuantite($value);
            $article->setPrixFixe($produit->getPrix());
            
            $articleRepository->save($article, false);
        }
        
        $commande->setTotal($total);
       
        $em->flush();
       
        $session->remove('panier');
        
        
        return $this->redirectToRoute('adresse_commande', ['id' => $commande->getId()]);
    }
  /**
   * @Route("/commande/adresse/{id}", name="adresse_commande")
   */
  public function adresse(Commande $commande, Request $request, AdresseRepository $adresseRepository):Response
{
    $adresse = new Adresse; 
    $form = $this->createForm(AdresseType::class, $adresse);

    $form->handleRequest($request);

    if($form->isSubmitted() AND $form->isValid())
    {
       $user = $this->getUser();
    
       $adresse->setUser($user);
       $adresse->addCommande($commande);

       $adresseRepository->save($adresse, true);
       return $this->redirectToRoute('payement');
    }
    return $this->render('adresse/index.html.twig', [
        'form' => $form->createView()
    ]);
}

/**
 * @Route("payement", name="payement")
 */
public function payement()
{
    return $this->render('payement/payement.html.twig');
}
#[Route('/commande_passe', name:'commande_passe')]
public function commande_passe(CommandeRepository $commandeRepository):Response
{
    $user = $this->getUser();
    
    $commande = $user->getCommandes();
    return $this -> render('profil/commande_passe.html.twig', [
        'commandes' => $commande,
    ]);
}
  
}
