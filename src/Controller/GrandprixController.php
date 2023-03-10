<?php

namespace App\Controller;

use App\Entity\Circuit;
use App\Entity\Reservation;
use App\HttpClient\F1HttpClient;
use App\HttpClient\WeatherHttpClient;
use App\Repository\EmplacementRepository;
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

    #[Route('/grandprix/{id}/reservation', name: 'app_reservation')]
    public function reservation(Request $request, F1HttpClient $f1, EmplacementRepository $er): Response
    {
        $id = $request->attributes->get('_route_params');
        $round = substr($id['id'],5,7);
        $year = substr($id['id'],0,4);
        $grandprix = $f1->getDetailsGrandprix($year, $round);
        $circuit = json_decode($grandprix,true)["MRData"]["RaceTable"]["Races"]["0"]["Circuit"]["circuitId"];
        $emplacements = $er->getEmplacementsByCircuit($circuit);

        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'route_param' => $id,
            'emplacements' => $emplacements,
            'circuit' => $circuit,
        ]);
    }

    // #[Route('/grandprix/{id}/reservation', name: 'creer_reservation')]
    // public function ajouterProgramme(ManagerRegistry $doctrine)
    // { 
    //     if($this->getUser()){
            
    //         if(isset($_POST['submitReservation'])){
    //             $user = $this->getUser();
    //             $grandprix = $request->attributes->get('_route_params');
    //             $session =filter_input(INPUT_POST,"session",FILTER_SANITIZE_SPECIAL_CHARS);
    //             $emplacement =filter_input(INPUT_POST,"emplacement",FILTER_SANITIZE_SPECIAL_CHARS);
    //             $nbPersonnes =filter_input(INPUT_POST,"nbPersonnes",FILTER_VALIDATE_INT);
    //             dd($nbPersonnes);
    //             if($nbpersonnes > 0){
    //                 $entityManager = $doctrine->getManager();
    //                 $reservation = new Reservation();
    //                 $reservation->setUser($user);
    //                 $reservation->setGrandprix($grandprix);
    //                 $reservation->setSession($session);
    //                 $reservation->setEmplacement($emplacement);
    //                 $reservation->setNbPersonnes($nbPersonnes);
    //                 $entityManager->persist($reservation);
    //                 $entityManager->flush();
    //                 return $this->redirectToRoute('app_reservation', ['id' => $request->attributes->get('_route_params')]);
    //             }
    //         }
    //     } else {
    //         return $this->redirectToRoute('app_login');
    //         }
    // }
}
