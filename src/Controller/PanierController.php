<?php

namespace App\Controller;


use App\Entity\Panier;
use App\Entity\Article;
use App\Entity\Produit;
use App\Form\PanierType;
use App\Repository\UserRepository;

use App\Repository\PanierRepository;

use App\Repository\ArticleRepository;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/panier")
 */
class PanierController extends AbstractController
{
    #[Route('/', name: 'panier_index', methods: ['GET'])]
    public function index(SessionInterface $session, ProduitRepository $produitRepository, ) 
    {   
        
        $panier = $session->get("panier", []);//On recuperer la session panier et si jamais elle est vide on recuperer un tableau vide 
        //On fabrique les données 
        $dataPanier = [];//créer une variable et on l'initialise a un tableau vide 
        $total = 0;//sers a calculer le total des prix 

        foreach($panier as $id => $quantite){//sert a bouclée sur le panier pour y recuperer l'id et la quantité
            
              $produit = $produitRepository->find($id);//Permet de recuperer un produit grace a la methode findAll() à partir de sont identifiant 
             
            

              $dataPanier[] = [  //Créer un tableau pour stocker des information sur un panier, la clé "produit" contiendrait le nom ou la référence du produit
                //et la clés "quantité" contiendrait la quantité de se produit dans le panier.
                "produit" => $produit,
               // "prix" => $produit->getPrix(),
                "quantite" => $quantite
               ];
              $total += $produit->getPrix() * $quantite;//permet de calculer d'un produit par la quantité commandé et en ajoutant ce montant au coût total deja accumulé.
             
        }
        
        return $this->render('panier/index.html.twig', [
            'dataPanier' => $dataPanier,
            'total' => $total,
        ]);
    }



}