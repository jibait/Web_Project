<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{

    // route which will be used to display the login page
    #[Route('/notAllowed', name: 'app_not_allowed', methods: ['GET'])]
      public function notAllowed(): Response
    {

      return $this->render('error/notAllowed.html.twig', [
     ]);
    }
}
