<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Reponse;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentairesController extends AbstractController
{
    #[Route('/post/{id}/delete', name: 'delete_post')]
    public function deletePost(ManagerRegistry $doctrine, Post $post)
    { 
        if($this->getUser() == $post->getUser()){
            $post->setStatut(1);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');        
        }else {
            return $this->redirectToRoute('app_home');
            }
    }  

    #[Route('/reponse/{id}/delete', name: 'delete_reponse')]
    public function deleteReponse(ManagerRegistry $doctrine, Reponse $reponse)
    { 
        if($this->getUser() == $reponse->getUser()){
            $entityManager = $doctrine->getManager();
            $entityManager->remove($reponse);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('app_home').'#commentaires');        
        }else {
            return $this->redirectToRoute('app_home');
            }
    }

    #[Route('/like/post/{id}', name: 'like_post')]
    public function likePost(Post $post, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        if($post->isLikedByUser($user)) {
            $post->removeLike($user);
            $manager->flush();

            return $this->json(['nbLike' => count($post->getLikes()) ]);
        }

        $post->addLike($user);
        $manager->flush();

        return $this->json(['nbLike' => count($post->getLikes()) ]);
    }

    #[Route('/like/reponse/{id}', name: 'like_reponse')]
    public function likeReponse(Reponse $reponse, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        if($reponse->isLikedByUser($user)) {
            $reponse->removeLike($user);
            $manager->flush();

            return $this->json(['nbLike' => count($reponse->getLikes()) ]);
        }

        $reponse->addLike($user);
        $manager->flush();

        return $this->json(['nbLike' => count($reponse->getLikes()) ]);
    }
}
