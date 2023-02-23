<?php

namespace App\Controller;

use App\HttpClient\BGAHttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SaisonController extends AbstractController
{
    #[Route('/saison', name: 'app_saison')]
    public function index(): Response
    {
        return $this->render('saison/index.html.twig', [
            'controller_name' => 'SaisonController',
        ]);
    }

    #[Route('/grandsprix', name: 'app_grandsprix', methods: ['POST'])]
    public function displayGrandsprix(BGAHttpClient $bga, Request $request) {
        $year = $request->request->get('year');
        return new Response($bga->getGrandsprix($year));  
    }

    #[Route('/driverStandings', name: 'app_driverStandings', methods: ['POST'])]
    public function displayDriverStandings(BGAHttpClient $bga, Request $request) {
        $year = $request->request->get('year');
        return new Response($bga->getDriverStandings($year));  
    }

    #[Route('/constructorStandings', name: 'app_construcyotStandings', methods: ['POST'])]
    public function displayConstructorStandings(BGAHttpClient $bga, Request $request) {
        $year = $request->request->get('year');
        return new Response($bga->getConstructorStandings($year));
    }
}
