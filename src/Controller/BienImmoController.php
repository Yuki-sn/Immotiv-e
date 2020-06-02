<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\BienImmobilierFormType;
use App\Entity\BienImmo;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    public function achat(Request $request, PaginatorInterface $paginator)
    {
        // On récupère dans l'url la données GET page (si elle n'existe pas, la valeur retournée par défaut sera la page 1)
        $requestedPage = $request->query->getInt('page', 1);

        // Si le numéro de page demandé dans l'url est inférieur à 1, erreur 404
        if($requestedPage < 1){
            throw new NotFoundHttpException();
        }

        // Récupération du manager des entités
        $em = $this->getDoctrine()->getManager();

        // Création d'une requête qui servira au paginator pour récupérer les articles de la page courante
        $query = $em->createQuery('SELECT a FROM App\Entity\BienImmo a WHERE a.typeOfPropriete = 1 ORDER BY a.datePublicationAddImmo DESC');

        // On stocke dans $pageImmobilere les 10 articles de la page demandée dans l'URL
        $pageImmobilere = $paginator->paginate(
            $query,     // Requête de selection des articles en BDD
            $requestedPage,     // Numéro de la page dont on veux les articles
            4     // Nombre d'articles par page
        );

        return $this->render('bien_immo/achat.html.twig', [
            'annonces' => $pageImmobilere
        ]);

    }

    /**
     * @Route("/louer/", name="main_louer")
     */
    public function louer(Request $request, PaginatorInterface $paginator)
    {
        // On récupère dans l'url la données GET page (si elle n'existe pas, la valeur retournée par défaut sera la page 1)
        $requestedPage = $request->query->getInt('page', 1);

        // Si le numéro de page demandé dans l'url est inférieur à 1, erreur 404
        if($requestedPage < 1){
            throw new NotFoundHttpException();
        }

        // Récupération du manager des entités
        $em = $this->getDoctrine()->getManager();

        // Création d'une requête qui servira au paginator pour récupérer les articles de la page courante
        $query = $em->createQuery('SELECT a FROM App\Entity\BienImmo a WHERE a.typeOfPropriete = 0 ORDER BY a.datePublicationAddImmo DESC');

        // On stocke dans $pageImmobilere les 10 articles de la page demandée dans l'URL
        $pageImmobilere = $paginator->paginate(
            $query,     // Requête de selection des articles en BDD
            $requestedPage,     // Numéro de la page dont on veux les articles
            4      // Nombre d'articles par page
        );

        return $this->render('bien_immo/louer.html.twig', [
            'annonces' => $pageImmobilere
        ]);

    }


}
