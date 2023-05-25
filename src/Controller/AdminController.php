<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/user/{id}/ban', name: 'ban_user')]
    public function banUser(ManagerRegistry $doctrine, User $user, Security $security)
    { 
        if($security->isGranted('ROLE_ADMIN')){ 
            $user->setStatut(1);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);

            $reponses = $user->getReponses();
            foreach ($reponses as $reponse){
                $entityManager->remove($reponse);
            }

            $posts = $user->getPosts();
            foreach ($posts as $post){
                $post->setStatut(1);
                $entityManager->persist($post);
            }
            
            $entityManager->flush();
            return $this->redirectToRoute('app_home');        
        }else {
            return $this->redirectToRoute('app_home');
            }
    }  
}
