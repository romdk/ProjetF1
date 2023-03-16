<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Grandprix;
use App\Form\ReponseType;
use App\HttpClient\F1HttpClient;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ManagerRegistry $doctrine, Post $post = null, Request $request, Grandprix $grandprix = null, F1HttpClient $f1): Response
    {
        $postForm = $this->createForm(PostType::class, $post);
        $postForm->handleRequest($request);

        $reponseForm = $this->createForm(ReponseType::class, $post);
        $reponseForm->handleRequest($request);
        

        $lastGrandprix = $f1->getLastRaceResults();
        $season = json_decode($lastGrandprix,true)["MRData"]["RaceTable"]['season'];
        $round = json_decode($lastGrandprix,true)["MRData"]["RaceTable"]['round'];
        $grandprix = $doctrine->getRepository(Grandprix::class)->findOneBy(['season' => $season, 'round' => $round],[]);
        $posts = $grandprix->getPosts();

        foreach ($posts as $post) {
            $reponses = $post->getReponses();
        };

        if($postForm->isSubmitted() && $postForm->isValid()) {  
            $post = $postForm->getData();
            $entityManager = $doctrine->getManager();
            $post->setDateCreation(new \DateTime());
            $post->setUser($this->getUser());
            $post->setGrandprix($grandprix);
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('app_home').'#commentaires');
        }

        if($reponseForm->isSubmitted() && $reponseForm->isValid()) {
            // récupere l'id du post depuis le formulaire
            $postId = $reponseForm->get("postId")->getData();

            // on retrouve le post correspondant grâce à l'id 
            $post = $doctrine->getRepository(Post::class)->find($postId);
            
            $reponse = $reponseForm->getData();
            $entityManager = $doctrine->getManager();
            $reponse->setDateCreation(new \DateTime());
            $reponse->setUser($this->getUser());
            $reponse->setPost($post);
            $entityManager->persist($reponse);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('app_home').'#commentaires');
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'formAddPost' => $postForm->createView(),
            'posts' => $posts,
            'formAddReponse' => $reponseForm->createView(),
            'reponses' => $reponses,
            'gp' => $grandprix
        ]);
    }

    #[Route('/grandsprix', name: 'app_grandsprix', methods: ['POST'])]
    public function displayGrandsprix(F1HttpClient $f1, Request $request) {
        $year = $request->request->get('year');
        return new Response($f1->getGrandsprix($year));  
    }

    #[Route('/lastResults', name: 'app_lastResults', methods: ['POST'])]
    public function displayLastResults(F1HttpClient $f1, Request $request) {
        return new Response($f1->getLastRaceResults());
    }
}
