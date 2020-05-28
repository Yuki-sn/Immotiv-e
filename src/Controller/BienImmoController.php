<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\BienImmobilierFormType;

class BienImmoController extends AbstractController
{
    /**
     * @Route("/ajouter_un_bien_immobilier/", name="main_immo")
     */
    public function bienImmo()
    {
        $imo = new BienImmo();
        
        return $this->render('registration/register.html.twig', [
            'addimmoform' => $form->createView(),
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
