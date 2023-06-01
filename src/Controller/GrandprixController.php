<?php

namespace App\Controller;

use App\Entity\Circuit;
use App\Entity\Reservation;
use App\HttpClient\F1HttpClient;
use App\HttpClient\WeatherHttpClient;
use App\Repository\EmplacementRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GrandprixController extends AbstractController
{

    #[Route('/grandprix/{id}', name: 'app_grandprix')]
    public function detail(Request $request, ManagerRegistry $doctrine,F1HttpClient $f1, $id): Response
    {
        $currDate = new \DateTime();
        $round = substr($id,5,7);
        $year = substr($id,0,4);
        $data = $f1->getDetailsGrandprix($year, $round);
        $grandprix = json_decode($data,true)["MRData"]["RaceTable"]["Races"]["0"];

        return $this->render('grandprix/detail.html.twig', [
            'id' => $id,
            'grandprix' => $grandprix,
            'currDate' => $currDate
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

    #[Route('/grandprix/{id}/reservation', name: 'app_reservation')]
    public function reservation(Request $request, F1HttpClient $f1, EmplacementRepository $er, $id): Response
    {
        if($this->getUser()){

            $round = substr($id,5,7);
            $year = substr($id,0,4);
            $grandprix = $f1->getDetailsGrandprix($year, $round);
            $circuit = json_decode($grandprix,true)["MRData"]["RaceTable"]["Races"]["0"]["Circuit"]["circuitId"];
            $emplacements = $er->getEmplacementsByCircuit($circuit);
            $dateGrandprix =  json_decode($grandprix,true)["MRData"]["RaceTable"]["Races"]["0"]["date"];
            $currDate = date('Y-m-d');

            if ($dateGrandprix > $currDate){
                return $this->render('reservation/index.html.twig', [
                    'controller_name' => 'ReservationController',
                    'route_param' => $id,
                    'emplacements' => $emplacements,
                    'circuit' => $circuit,
                ]);
            }else{
                return $this->redirectToRoute('app_grandprix', ['id' => $id]);            
            }
        }else{
            return $this->redirectToRoute('app_login');
        }
    }
}
