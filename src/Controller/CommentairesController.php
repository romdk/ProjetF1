<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Reponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentairesController extends AbstractController
{
    #[Route('/post/{id}/delete', name: 'delete_post')]
    public function deletePost(ManagerRegistry $doctrine, Post $post)
    { 
        if($this->getUser()){
            $entityManager = $doctrine->getManager();
            $entityManager->remove($post);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('app_home').'#commentaires');        
        }else {
            return $this->redirectToRoute('app_login');
            }
    }  

    #[Route('/reponse/{id}/delete', name: 'delete_reponse')]
    public function deleteReponse(ManagerRegistry $doctrine, Reponse $reponse)
    { 
        if($this->getUser()){
            $entityManager = $doctrine->getManager();
            $entityManager->remove($reponse);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('app_home').'#commentaires');        
        }else {
            return $this->redirectToRoute('app_login');
            }
    }    
}
