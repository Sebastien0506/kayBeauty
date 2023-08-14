<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Article;
use App\Entity\Produit;
use App\Entity\ArticlePanier;
use App\Form\ArticlePanierType;
use App\Repository\UserRepository;
use App\Repository\PanierRepository;
use App\Repository\ArticleRepository;
use App\Repository\ProduitRepository;
use App\Repository\ArticlePanierRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

#[Route('/article/panier')]
class ArticlePanierController extends AbstractController
{
     /**
     * @Route("/add/{id}", name="panier_add")
     * 
     */
    public function add($id, Produit $produit, SessionInterface $session){
      //Permet de créer un nouveau panier ou de rajouter un produit si le panier existe deja 
        // On recupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $produit->getId();
       
        //si le panier n'est pas vide on l'incrémente de 1
       if(!empty ($panier[$id])){
           $panier[$id]++;
       }
       //si le panier est vide on ajoute 1
       else{
            $panier[$id] = 1;
       }
    
     //ON sauvegarde dans la session
    //  dd($panier);
     $session->set("panier", $panier);
     
     return $this->redirectToRoute("panier_index");
    
   }

    

     /**
     * @Route("/validez", name="validation_panier")
     */
    public function validez_panier(PanierRepository $panierRepository, SessionInterface $session, ProduitRepository $produitRepository, UserRepository $userRepository, ArticleRepository $articleRepository ):Response
    {   //Permet d'inserer le panier en base de donnée 
        $panierSession = $session->get("panier");//On assigne la valeur $session->get("panier") a la variable $panierSession 

        $user = $this->getUser();

        $panier = $panierRepository->findOneByUser($user);//On dit que $panier est un nouveau panier 
         if($panier == null){
            $panier = new Panier;
            $panier->setUser($user);
         }
         
        foreach($panier->getArticle() as $article){
            $articleRepository->remove($article, true);
        }

        foreach($panierSession as $key=>$value){//On fait une boucle sur chaque produit du panier

            $article = new Article();//On définit les propriété de l'instance Article en utilisant les données récupérées

            $produit = $produitRepository->find($key);
            $article->setPanier($panier);
            $article->setProduit($produit);
            $article->setQuantite($value);
            $article->setPrixFixe($produit->getPrix());
            $articleRepository->save($article, true);
        }
  
           $panierRepository->save($panier, true);//On sauvegarde l'instance panier dans la base de données via la méthode save de $panierRepository
        
            $this->addFlash('success','Votre panier a bien été validez');//On ajoute un message 
            
        return $this->redirectToRoute("home");//On est rediriger vers la page home

    }

    

    /**
    * @Route("/delete/{id}", name="delete")
    */
    public function delete($id, Produit $produit, SessionInterface $session)
    {
      //On récupère le panier dans la session  
      $panier = $session->get("panier", []);

      //On récupère l'id du produit
      $id = $produit->getId();
      
     //On vérifie si le produit est présent dans le panier
     //Si il est présente et qu'il est supérieur a 1 on enlève 1 
     //Sinon on supprime le produit 
      if(!empty($panier[$id])){
        if($panier[$id] > 1){
             $panier[$id]--;
        }else{
           unset($panier[$id]); 
        }
    }
    //On sauvegarde le panier mise a jour dans la session 
      $session->set("panier", $panier);
      return $this->redirectToRoute("panier_index");
      
  }

     /**
     * @Route("/clear", name="validation_panier")
     */
    public function clearSessision(RequestStack $request):Response
    {
        $request->getSession()->clear();
        return $this->redirectToRoute("panier_index");
    }


  }
