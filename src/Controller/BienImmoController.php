<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\BienImmo;
use App\Form\BienImmobilierFormType;
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
        
        return $this->render('bien_immo/newBienImmobilier.html.twig', [
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

    /**
     * Page d'affichage d'une annonce en détail
     *
     * @Route("/annonce/{slug}/", name="annonce_view")
     */
    public function publicationView(BienImmo $BienImmo, Request $request){

        return $this->render('bien_immo/annonce_view.html.twig', [
            'bienimmo' => $BienImmo,
        ]);
  
    }

    /**
     * Page d'affichage d'une annonce en détail
     *
     * @Route("/toutes-mes-annonces/", name="all_annonce")
     */
    public function allAnnonces(Request $request , PaginatorInterface $paginator){
        // Récupération du numéro de la page demandée dans l'url (si il existe pas, 1 sera pris à la place)
        $requestedPage = $request->query->getInt('page', 1);

        // Si la page demandée est inférieur à 1, erreur 404
        if($requestedPage < 1){
            throw new NotFoundHttpException();
        }

        // Récupération du manager général des entités
        $em = $this->getDoctrine()->getManager();


        // Création d'une requête permettant de récupérer les annonce pour la page actuelle , par rapport a l'id de l'uilisateur
        $query = $em
            ->createQuery('SELECT a FROM App\Entity\BienImmo a WHERE a.author = :authorId ORDER BY a.datePublicationAddImmo DESC')
            ->setParameters(['authorId' => $this->getUser()->getId()])
        ;

        // Récupération des myAnnonce
        $myAnnonce = $paginator->paginate(
            $query,
            $requestedPage,
            4
        );
        
        return $this->render('bien_immo/myallannonces.html.twig',[
            'myAnnonce' => $myAnnonce
        ]);
  
    }

    /**
     * @Route("/suppression/{id}/", name="bien_delet")
     */
    public function publicationDelete(BienImmo $BienImmo, Request $request){

        // Si le token CSRF passé dans l'url n'est pas le token valide, message d'erreur
        if(!$this->isCsrfTokenValid('bien_delet'. $BienImmo->getId(), $request->query->get('csrf_token'))){

            $this->addFlash('error', 'Token sécurité invalide, veuillez ré-essayer.');

        } else {

            // Suppression de l'BienImmo via le manager général des entités
            $em = $this->getDoctrine()->getManager();
            $em->remove($BienImmo);
            $em->flush();

            // Message flash de type "success" pour indiquer la réussite de la suppression
            $this->addFlash('success', 'La publication a été supprimé avec succès!');

        }

        // Redirection de l'utilisateur sur la liste des BienImmo
        return $this->redirectToRoute('main_home');
    }

    /**
     * @Route("/modifier-un-bien/{id}/", name="immo_edit")
     */
    public function publicationEdit(BienImmo $BienImmo, request $request){

        // Création du formulaire de modification d'article (c'est le même que le formulaire permettant de créer un nouvel article, sauf qu'il sera déjà rempli avec les données de l'article existant "$article")
        $form = $this->createForm(BienImmobilierFormType::class, $BienImmo);

        // Liaison des données de requête (POST) avec le formulaire
        $form->handleRequest($request);

        // Si le formulaire est envoyé et n'a pas d'erreur
        if($form->isSubmitted() && $form->isValid()){

            // Sauvegarde des changements faits dans l'article via le manager général des entités
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            // Message flash de type "success"
            $this->addFlash('success', 'Article modifié avec succès !');

            // Redirection vers la page de l'article modifié
            return $this->redirectToRoute('annonce_view', ['slug' => $BienImmo->getSlug()]);

        }

        // Appel de la vue en lui envoyant le formulaire à afficher
        return $this->render('bien_immo/editBienImmo.html.twig', [
            'formForImo' => $form->createView()
        ]);

    }



    


}
