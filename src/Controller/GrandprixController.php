<?php

namespace App\Controller;

use App\Entity\Circuit;
use App\Entity\Grandprix;
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
        $round = substr($id,5,7);
        $year = substr($id,0,4);
        $grandprix = $doctrine->getRepository(Grandprix::class)->findOneBy(['season' => $year, 'round' => $round],[]);

        if($grandprix) {
            $currDate = new \DateTime();
            $data = $f1->getDetailsGrandprix($year, $round);
            $grandprixData = json_decode($data,true)["MRData"]["RaceTable"]["Races"]["0"];
            
            return $this->render('grandprix/detail.html.twig', [
                'id' => $id,
                'grandprix' => $grandprixData,
                'currDate' => $currDate
            ]);
        }else {
            return $this->redirectToRoute('app_saison');
        }
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
    public function reservation(Request $request, F1HttpClient $f1, ManagerRegistry $doctrine, EmplacementRepository $er, $id): Response
    {
        // si l'utilisateur est connecté
        if($this->getUser()){

            // on récupère le round et l'année depuis l'id de la route
            $round = substr($id,5,7);
            $year = substr($id,0,4);
            $grandprix = $doctrine->getRepository(Grandprix::class)->findOneBy(['season' => $year, 'round' => $round],[]);


            if ($grandprix) {
            
                // puis avec ces informations on récupère les données du grandprix depuis l'api
                $grandprixData = $f1->getDetailsGrandprix($year, $round);

                // on récupère ensuite l'id du circuit
                $circuit = json_decode($grandprixData,true)["MRData"]["RaceTable"]["Races"]["0"]["Circuit"]["circuitId"];

                // l'id du circuit permet de récuperer la liste des emplacements
                $emplacements = $er->getEmplacementsByCircuit($circuit);

                // on récupère également la date du grandprix
                $dateGrandprix =  json_decode($grandprixData,true)["MRData"]["RaceTable"]["Races"]["0"]["date"];

                // puis on récupère la date du jour
                $currDate = date('Y-m-d');

                // on compare nos 2 dates pour faire une conditions
                if ($dateGrandprix > $currDate){
                    return $this->render('reservation/index.html.twig', [
                        'route_param' => $id,
                        'emplacements' => $emplacements,
                        'circuit' => $circuit,
                    ]);
                }else{
                    return $this->redirectToRoute('app_grandprix', ['id' => $id]);            
                }
            }else{
                return $this->redirectToRoute('app_saison');
            }
        }else{
            return $this->redirectToRoute('app_login');
        }
    }
}
