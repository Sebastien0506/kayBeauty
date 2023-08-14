<?php

namespace App\Controller;

use App\Entity\Logo;
use App\Form\LogoType;
use App\Repository\LogoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/logo')]
class LogoController extends AbstractController
{

    #[Route('/new', name: 'app_logo_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LogoRepository $logoRepository, EntityManagerInterface $em): Response
    {
        $logo = new Logo();
        $form = $this->createForm(LogoType::class, $logo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
             
            $existingLogo = $logoRepository->findOneBy([]);

            if($existingLogo){
                //On supprime l'ancien logo si il existe
                $em->remove($existingLogo);
            }

            $em -> persist($logo);
            
            $em -> flush();

            $this -> addFlash('success', 'Logo ajouté avec succès');

            return $this -> redirectToRoute('home');
        }

        return $this->render('logo/create_logo.html.twig', [
            'formLogo' => $form->createView()
        ]);
    }
}
