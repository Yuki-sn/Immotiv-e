<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\BienImmobilierFormType;
use App\Entity\BienImmo;
use DateTime;

class BienImmoController extends AbstractController
{
    /**
     * @Route("/ajouter_un_bien_immobilier/", name="main_immo")
     */
    public function bienImmo(Request $request): Response
    {
        // Redirige de force vers l'accueil si l'utilisateur est pas connecté
        if(!$this->getUser()) {
            return $this->redirectToRoute('main_home');
        }

        $imo = new BienImmo();
        $form = $this->createForm(BienImmobilierFormType::class, $imo);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $imo
               ->setdatePublicationAddImmo(new DateTime())
               ->setAuthor($this->getUser())
            ;            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($imo);
            $entityManager->flush();

            // Message flash de type "success"
            $this->addFlash('success', 'Annonce publié avec succès !');
            
            return $this->redirectToRoute('main_home');
        }
        
        return $this->render('bien_immo/addbienimo.html.twig', [
            'formForImo' => $form->createView(),
        ]);
    }

    /**
     * @Route("/acheter/", name="main_achat")
     */
    public function achat()
    {
        return $this->render('bien_immo/achat.html.twig',);
    }

    /**
     * @Route("/louer/", name="main_louer")
     */
    public function louer()
    {
        return $this->render('bien_immo/louer.html.twig',);
    }


}
