<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Reponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(Security $security, ManagerRegistry $doctrine): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            $users = $doctrine->getRepository(User::class)->findAll();
            $posts = $doctrine->getRepository(Post::class)->findAll();
            $reponses = $doctrine->getRepository(Reponse::class)->findAll();
            
            return $this->render('admin/index.html.twig', [
                'users' => $users,
                'posts' => $posts,
                'reponses' => $reponses,
            ]);
        } else {
            return $this->redirectToRoute('app_home');
        }
        
    }

    #[Route('/admin/{id}', name: 'app_admin_messages')]
    public function messages(Security $security, ManagerRegistry $doctrine, $id): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            $users = $doctrine->getRepository(User::class)->findAll();
            $posts = $doctrine->getRepository(Post::class)->findAll();
            $reponses = $doctrine->getRepository(Reponse::class)->findAll();
            
            return $this->render('admin/messages.html.twig', [
                'users' => $users,
                'posts' => $posts,
                'reponses' => $reponses,
                'id' => $id,
            ]);
        } else {
            return $this->redirectToRoute('app_home');
        }
        
    }

    #[Route('/user/{id}/ban', name: 'ban_user')]
    public function banUser(ManagerRegistry $doctrine, User $user, Security $security)
    { 
        if($security->isGranted('ROLE_ADMIN')){ 
            $entityManager = $doctrine->getManager();

            if($user->isStatut() == 0){
                $user->setStatut(1);
    
                $reponses = $user->getReponses();
                foreach ($reponses as $reponse){
                    $entityManager->remove($reponse);
                }
    
                $posts = $user->getPosts();
                foreach ($posts as $post){
                    $post->setStatut(1);
                    $entityManager->persist($post);
                }
            }
            elseif($user->isStatut() == 1){
                $user->setStatut(0);
            }
            
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin');        
        }else {
            return $this->redirectToRoute('app_home');
            }
    }  
}
