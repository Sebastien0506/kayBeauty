<?php

namespace App\Controller;

use DateTime;
use App\Entity\Calendar;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'app_api')]
    public function index(): Response
    {
        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
   /**
    * @Route("/api/{id}/edit", name="api_event_edit", methods={"PUT"})
    */
    public function majEvent(?Calendar $calendar, Request $request, EntityManagerInterface $em): Response
    {
       //On récupère les données
       $donnees = json_decode($request->getContent());

       //On vérifie que l'on a bien tout les données necessaire
       if(
        isset($donnees->title) && !empty($donnees->title) &&
        isset($donnees->start) && !empty($donnees->start) &&
        // isset($donnees->end) && !empty($donnee->end) &&
        isset($donnees->description) && !empty($donnees->description) &&
        isset($donnees->backgroundColor) && !empty($donnees->backgroundColor) &&
        isset($donnees->borderColor) && !empty($donnees->borderColor) &&
        isset($donnees->textColor) && !empty($donnees->textColor) 
       ){
        //les données sont complètes
        //On initialise un code 
        $code = 200;

        //On vérifie si l'id existe 
        if(!$calendar){
            //On instancie un rendez-vous
            $calendar = new Calendar;

            //On change le code 
            $code = 201;
        }
        //On hydra l'objet avec nos données 
        $calendar->setTitle($donnees->title);
        $calendar->setStart(new DateTime($donnees->start));
        $calendar->setEnd(new DateTime($donnees->end));
        $calendar->setDescription($donnees->description);
        $calendar->setBackgroundColor($donnees->backgroundColor);
        $calendar->setBorderColor($donnees->borderColor);
        $calendar->setTextColor($donnees->textColor);

        
        $em->persist($calendar);
        $em->flush();

        //On retourne un code 
        return new Response('ok', $code);
       }else{
        //les données ne sont pas complètes 
        return new Response('Données incomplètes', 404);
       }

        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }
}
