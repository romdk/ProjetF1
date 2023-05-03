<?php

namespace App\Controller;

use App\HttpClient\F1HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(F1HttpClient $f1): Response
    {
        // on récupère les reservations de l'utilisateur connecté
        $reservations = $this->getUser()->getReservations();
        // on créer un tableau associatif
        $grandprix = array();
        // pour chaque réservation
        foreach($reservations as $reservation){
            // on récupère la saison et le round du grandprix de la réservation
            $year = $reservation->getGrandprix()->getSeason();
            $round = $reservation->getGrandprix()->getRound();
            // puis on récupère les données du grandprix depuis l'api
            $data = $f1->getDetailsGrandprix($year, $round);
            // on remplis le tableau associatif avec comme clé l'id de la réservation et comme valeur le nom du grandprix
            $grandprix[$reservation->getId()] = json_decode($data,true)["MRData"]["RaceTable"]["Races"]["0"]["raceName"];
        }

        return $this->render('profil/index.html.twig', [
            'reservations' => $reservations,
            'grandprix' => $grandprix,
        ]);
    }
}   
