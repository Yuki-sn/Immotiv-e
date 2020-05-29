<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Entity\BienImmo;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function home(Request $request, PaginatorInterface $paginator)
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
        $query = $em->createQuery('SELECT a FROM App\Entity\BienImmo a');

        // On stocke dans $pageImmobilere les 10 articles de la page demandée dans l'URL
        $pageImmobilere = $paginator->paginate(
            $query,     // Requête de selection des articles en BDD
            $requestedPage,     // Numéro de la page dont on veux les articles
            3      // Nombre d'articles par page
        );

        return $this->render('main/index.html.twig', [
            'annonces' => $pageImmobilere
        ]);
    }

   /**
     * Page d'affichage d'une annonce en détail
     *
     * @Route("/annonce/{slug}/", name="annonce_view")
     */
    public function publicationView(BienImmo $BienImmo, Request $request){

        return $this->render('main/annonce_view.html.twig', );
  
    }

   
}
