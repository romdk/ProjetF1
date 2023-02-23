<?php

namespace App\Controller;

use App\HttpClient\BGAHttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/grandsprix', name: 'app_grandsprix', methods: ['POST'])]
    public function displayGrandsprix(BGAHttpClient $bga, Request $request) {
        $year = $request->request->get('year');
        return new Response($bga->getGrandsprix($year));  
    }

    #[Route('/lastResults', name: 'app_lastResults', methods: ['POST'])]
    public function displayLastResults(BGAHttpClient $bga, Request $request) {
        return new Response($bga->getLastRaceResults());
    }
}
