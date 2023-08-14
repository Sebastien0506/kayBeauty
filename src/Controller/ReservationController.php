<?php

namespace App\Controller;

use DateTime;
use App\Entity\Reservation;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManager;
use App\Repository\CalendarRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use DateTimeInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation, true);
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
//     /**
//      * @Route("gestion_reservation", name="gestion_reservation")
//      */
//     public function gestion_reservation(ReservationRepository $reservationRepository, CalendarRepository $calendar):Response
// {
//     $events = $calendar->findAll();

//         $rdvs = [];

//         foreach($events as $event){
//             $rdvs[] = [
//                 'id' => $event->getId(),
//                 'start' => $event->getStart()->format('Y-m-d H:i:s'),
//                 'end' => $event->getEnd()->format('Y-m-d H:i:s'),
//                 'description' => $event->getDescription(),
//                 'backgroundColor' => $event->getBackgroundColor(),
//                 'borderColor' => $event->getBorderColor(),
//                 'textColor' => $event->getTextColor(),
//             ];
//         }
//         $data = json_encode($rdvs);

//   return $this->render('reservation/gestion_reservation.html.twig', compact('data'));
// }
#[Route("/reservation_prestation/{id}", name:"reservation_prestation")]
public function reservation_prestation($id, SessionInterface $session):Response
{
    

     $event = $session->get('event');
     
// dd($event);
    return $this -> render('front/rendez_vous.html.twig', [
        'event' => $event
    ]);
    
}

 #[Route("/store_reservation", name:"store_reservation", methods:["POST"])]
public function store_reservation(Request $request, EntityManagerInterface $em):Response
{
    if($request->getMethod() != 'POST'){
        return new JsonResponse(['status' => 'error', 'message' => 'Invalid request method']);
    }
   
    $data = json_decode($request->getContent(), true);

    if(!isset($data['prestation']) || !isset($data['date']) || !isset($data['dabut']) || !isset($data['fin'])){
        return new JsonResponse(['status' => 'erreor', 'message' => 'Missing data for reservation']);
    }

    //Créer une nouvelle reservation avec les donnée reçut
    $reservation =new Reservation();

    $reservation -> setPrestation($data['prestation']);
    $reservation -> setDate(new \DateTime($data['date']));
    $reservation -> setDebut(new \DateTime($data['debut']));
    $reservation -> setFin(new \DateTime($data['fin']));
    
    //On enregistre la reservation dans la base de donnée
    
    $em->persist($reservation);
    $em->flush();

    return new JsonResponse(['status' => 'success']);

}

}
