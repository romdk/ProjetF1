<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Grandprix;
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
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        $lastGrandprix = $f1->getLastRaceResults();
        $season = json_decode($lastGrandprix,true)["MRData"]["RaceTable"]['season'];
        $round = json_decode($lastGrandprix,true)["MRData"]["RaceTable"]['round'];
        $grandprix = $doctrine->getRepository(Grandprix::class)->findOneBy(['season' => $season, 'round' => $round],[]);
        $messages = $grandprix->getPosts();

        if($form->isSubmitted() && $form->isValid()) {  
            $post = $form->getData();
            $entityManager = $doctrine->getManager();
            $post->setDateCreation(new \DateTime());
            $post->setUser($this->getUser());
            $post->setGrandprix($grandprix);
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_home',);
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'formAddPost' => $form->createView(),
            'messages' => $messages,
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
