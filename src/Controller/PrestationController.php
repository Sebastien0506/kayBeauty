<?php

namespace App\Controller;

use App\Entity\Prestation;
use App\Form\PrestationType;
use Doctrine\ORM\EntityManager;
use App\Repository\PrestationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

 #[Route('/prestation')]
class PrestationController extends AbstractController
{
  

    /**
     * @Route("/ajouter", name="ajouter_prestation")
     */
    public function prestation_ajouter(Request $request, PrestationRepository $prestationRepository):Response
    {
        $prestation = new Prestation();

        $form = $this->createForm(PrestationType::class, $prestation);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $dureMinutes = $prestation->getDureMinutes();
            $dureHeures = $dureMinutes / 60;
            $prestation->setDureHeure($dureHeures);

            $prestationRepository->save($prestation, true);

            $this->addFlash('succes','La prestation a bine été ajouté');

            return $this->redirectToRoute('ajouter_prestation');
        }
        return $this->render('prestation/ajouter_prestation.html.twig', [
            'formPrestation' => $form->createView()
        ]);
    }

   /**
    * @Route("/gestion", name="gestion_prestation")
    */

    public function gestion_prestation(PrestationRepository $prestationRepository):Response
    {
      $prestations = $prestationRepository->findAll();
      
      return $this->render('prestation/gestion_prestation.html.twig', [
        'prestations' => $prestations
      ]);

    }

    /**
     * @Route("/modifier/{id}", name="prestation_modifier")
     */
    public function prestation_modifier(Prestation $prestation, Request $request, PrestationRepository $prestationRepository):Response
    {
      $form = $this->createForm(PrestationType::class, $prestation);

      $form->handleRequest($request);

      if($form->isSubmitted() AND $form->isValid())
      {
        
        $dureMinutes = $prestation->getDureMinutes();
        if($dureMinutes < 60){
          //Si la durée est inférieur  1h
          $dureHeures = 0;
          $dureMinutes = $dureMinutes;
        }else{
          //Affichage en heure et minute 
          $dureHeures = floor($dureMinutes / 60);
          $dureMinutes = $dureMinutes % 60;
        }
        

        
        $prestation->setDureHeure($dureHeures);
        $prestation->setDureMinutes($dureMinutes);
        $prestationRepository->save($prestation, true);
        
        $this->addFlash('succes','La prestation a bien été modifier');
       
        return $this->redirectToRoute('gestion_prestation');
      }

      return $this->render('prestation/prestation_modifier.html.twig', [
        'formPrestation' => $form->createView()
      ]);
      
    } 

    /**
     * @Route("/prestation/supprimer/{id}", name="prestation_supprimer")
     */
    public function prestation_supprimer(Prestation $prestation, PrestationRepository $prestationRepository)
    {

      $prestationRepository->remove($prestation, true);

      $this->addFlash('success','La prestation a bien été supprimer');

      return $this->redirectToRoute('gestion_prestation');
    }

 #[Route("/prestation_reservation/{id}", name: "prestation_reservation")]
  public function prestation_reservation($id, PrestationRepository $prestationRepository, SessionInterface $session, UserRepository $userRepository):Response
  {
     

    //  dd($user);
     $prestationId = $prestationRepository->find($id);

     $prestation = $prestationId;
     
     //On récupère la durer de la prestation en heure et minute 
     $dureHeures = $prestationId->getDureHeure();
     $dureMinutes = $prestationId->getDureMinutes();

     if($dureHeures > 0){
      $dureMinutes += $dureHeures * 60;
     }


    $event = [
        
        'id' => $prestation->getId(),
        'nom' => $prestation->getNom(),
        'dureMinutes' => $dureMinutes,
      ];
   
    
    // dd($event);
    $session->set('event', $event);
    // dd($session);
    return $this -> redirectToRoute("reservation_prestation", [
      'id' => $id
    ]);
  }
 }
