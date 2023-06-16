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
        $reponses = '';

        foreach ($posts as $post) {
            $reponses = $post->getReponses();
        };

        // Si le formulaire est envoyé et valide
        if($postForm->isSubmitted() && $postForm->isValid()) { 
            // on récupère les données du formulaire 
            $post = $postForm->getData();
            // on appelle le manager
            $entityManager = $doctrine->getManager();
            // on met en place la date de création du post
            $post->setDateCreation(new \DateTime());
            // on associe l'utilisateur au post
            $post->setUser($this->getUser());
            // on associe le grandprix au post
            $post->setGrandprix($grandprix);
            // on fait persister l'entité
            $entityManager->persist($post);
            // on push l'entité vers la BDD
            $entityManager->flush();
            // on redirige l'utilisateur vers la page d'accueil
            return $this->redirectToRoute('app_home');
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

            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/index.html.twig', [
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

    // route qui appelle la fonction getLastRaceResults du F1HttpClient
    #[Route('/lastResults', name: 'app_lastResults', methods: ['POST'])]
    public function displayLastResults(F1HttpClient $f1, Request $request) {
        return new Response($f1->getLastRaceResults());
    }

    #[Route('/confidentialite', name: 'app_confidentialite')]
    public function displayConfidentialite(){
        return $this->render('politique/confidentialite.html.twig');
    }

    #[Route('/cgu', name: 'app_cgu')]
    public function displayCgu(){
        return $this->render('politique/cgu.html.twig');
    }

    #[Route('/cgv', name: 'app_cgv')]
    public function displayCgv(){
        return $this->render('politique/cgv.html.twig');
    }
}
