<?php

namespace App\Controller;

use App\HttpClient\F1HttpClient;
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
    public function displayGrandsprix(F1HttpClient $f1, Request $request) {
        $year = $request->request->get('year');
        return new Response($f1->getGrandsprix($year));  
    }

    #[Route('/lastResults', name: 'app_lastResults', methods: ['POST'])]
    public function displayLastResults(F1HttpClient $f1, Request $request) {
        return new Response($f1->getLastRaceResults());
    }
}
