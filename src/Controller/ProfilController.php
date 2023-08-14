<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * @Route("/profil")
 */
class ProfilController extends AbstractController
{
  /**
   * @Route("/mon_compte", name="mon_compte")
   */
  public function mon_compte()
  {
    return $this->render('profil/mon_compte.html.twig');
  }

  /**
   * @Route("/parametre", name="connexion_securite")
   */
  public function connexion_securite():Response
  {
    $user = $this->getUser();
    
    return $this->render('profil/connexion_securite.html.twig', [
      'user' =>$user,
    ]);
  }

  /**
   * @Route("/modifier", name="profil_modifier")
   */
  public function profil_modifier(Request $request, UserRepository $userRepository)
  {
    
    $user = $this->getUser();

    $form = $this->createForm(RegistrationFormType::class, $user, [
      'nom' => true,
      'prenom' => true,
      'email' => true 
    ]);

    $form->handleRequest($request);

    if($form->isSubmitted() AND $form->isValid())
    {
       $userRepository->save($user, true);
       $this->addFlash('success',' Votre profil a bien été modifier');
       return $this->redirectToRoute('mon_compte');
    }


    return $this->render('profil/profil_modifier.html.twig', [
      'formUser' => $form->createView()
    ]);
  }

}
