<?php

namespace App\Controller;

use App\HttpClient\F1HttpClient;
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
    public function displayGrandsprix(F1HttpClient $f1, Request $request) {
        $year = $request->request->get('year');
        return new Response($f1->getGrandsprix($year));  
    }

    #[Route('/driverStandings', name: 'app_driverStandings', methods: ['POST'])]
    public function displayDriverStandings(F1HttpClient $f1, Request $request) {
        $year = $request->request->get('year');
        return new Response($f1->getDriverStandings($year));  
    }

    #[Route('/constructorStandings', name: 'app_construcyotStandings', methods: ['POST'])]
    public function displayConstructorStandings(F1HttpClient $f1, Request $request) {
        $year = $request->request->get('year');
        return new Response($f1->getConstructorStandings($year));
    }
}
