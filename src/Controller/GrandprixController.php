<?php

namespace App\Controller;

use App\HttpClient\F1HttpClient;
use App\HttpClient\WeatherHttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GrandprixController extends AbstractController
{

    #[Route('/grandprix/{id}', name: 'app_grandprix')]
    public function detail(Request $request): Response
    {
        $id = $request->attributes->get('_route_params');
        return $this->render('grandprix/detail.html.twig', [
            'route_param' => $id
        ]);
    }

    #[Route('/detailsGrandprix', name: 'details_grandprix', methods: ['POST'])]
    public function displayGrandprixDetails(F1HttpClient $f1, Request $request) {
        $year = $request->request->get('year');
        $round = $request->request->get('round');
        return new Response($f1->getDetailsGrandprix($year, $round));
    }

    #[Route('/meteoGrandprix', name: 'meteo_grandprix', methods: ['POST'])]
    public function displayGrandprixMeteo(WeatherHttpClient $weather, Request $request) {
        $latitude = $request->request->get('latitude');
        $longitude = $request->request->get('longitude');
        $dateDebut = $request->request->get('dateDebut');
        $dateFin = $request->request->get('dateFin');
        return new Response($weather->getWeather($latitude, $longitude, $dateDebut, $dateFin));
    }
}
