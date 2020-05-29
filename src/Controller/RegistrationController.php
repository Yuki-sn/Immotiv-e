<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Form\FormError;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription/", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder,MailerInterface $mailer): Response
    {
        // Redirige de force vers l'accueil si l'utilisateur est déjà connecté
        if ($this->getUser()) {
            return $this->redirectToRoute('main_home');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user
                ->setPassword($passwordEncoder->encodePassword($user,$form->get('plainPassword')->getData()))
                ->setActivated(false)
                ->setActivationToken( md5( random_bytes(100) ) )
            ;            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $email = (new TemplatedEmail())
            ->from(new Address('noreply@leblogdebatman.fr', 'coucou'))
            ->to($user->getEmail())
            ->subject('Activation de votre compte')
            ->htmlTemplate('security/emails/activation.html.twig')
            ->textTemplate('security/emails/activation.txt.twig')
            ->context([
                'user' => $user
            ]);

            // Envoi de l'email
            $mailer->send($email);
        ;

            // Message flash de type "success"
            $this->addFlash('success', 'Compte crée avec succès ! un email vous a etais envoyé');
            return $this->redirectToRoute('app_login');
    
        }    
       
        // Appel de la vue en envoyant le formulaire à afficher
        return $this->render('registration/register.html.twig', [
        'registrationForm' => $form->createView(),
    ]);
}}
