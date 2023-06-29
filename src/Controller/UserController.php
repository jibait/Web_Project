<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(Connection $connection, TokenStorageInterface $tokenStorage): Response
    {
        if($tokenStorage->getToken() != null){
            $user = $tokenStorage->getToken()->getUser();
        }

        else{
            return $this->redirectToRoute('app_login');
        }

        $userRoles = $user->getRoles();

        if(in_array('ROLE_ADMIN', $userRoles))
        {
            $sql = "SELECT `user_id`,`email`,`roles` FROM `user`";

            $result = $connection->executeQuery($sql)->fetchAllAssociative();
    
            return $this->render('user/index.html.twig', [
                'users' => $result
            ]);
        }

        else
        {
            return $this->redirectToRoute('app_not_allowed');
        }
    }

    #[Route('/edit/{id}', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository, TokenStorageInterface $tokenStorage): Response
    {
        if($tokenStorage->getToken()->getUser() != null){
            $user = $tokenStorage->getToken()->getUser();
        }

        else{
            return $this->redirectToRoute('app_login');
        }

        $userRoles = $user->getRoles();

        if(in_array('ROLE_ADMIN', $userRoles))
        {
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $userRepository->save($user, true);
    
                return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
            }
    
            return $this->render('user/edit.html.twig', [
                'user' => $user,
                'form' => $form,
            ]);
        }
        
        else
        {
            return $this->redirectToRoute('app_not_allowed');
        }
    }
}
