<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

  class LoginController extends AbstractController
  {

      // route which will be used to display the login page
      #[Route('/login', name: 'app_login')]
     public function index(AuthenticationUtils $authenticationUtils): Response
      {
        if($this->getUser() != null){
          return null;
        }

         // get the login error if there is one
         $error = $authenticationUtils->getLastAuthenticationError();

         // last username entered by the user
         $lastUsername = $authenticationUtils->getLastUsername();

          return $this->render('login/index.html.twig', [
             'controller_name' => 'LoginController',
             'last_username' => $lastUsername,
             'error'         => $error,
          ]);
      }

      // route which will be used to logout
      #[Route('/logout', name: 'app_log_out', methods: ['GET'])]
      public function someAction(Security $security): Response
    {

      if($this->getUser() != null){
        $security->logout(false);
      }

        // redirect to the login page
        return $this->redirectToRoute('app_login');
    }
  }