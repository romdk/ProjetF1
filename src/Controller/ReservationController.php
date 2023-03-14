<?php

namespace App\Controller;

use App\Entity\Circuit;
use App\Entity\Grandprix;
use App\Entity\Emplacement;
use App\Entity\Reservation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{

    
    #[Route('/grandprix/{id}/reservationCreate', name: 'creer_reservation')]
    public function creerReservation(ManagerRegistry $doctrine, $id)
    { 
        if($this->getUser()){       
            if(isset($_POST['session']) && isset($_POST['emplacement']) && isset($_POST['nbPersonnes'])){
                // on récupère les données du formulaire
                $session =filter_input(INPUT_POST,"session",FILTER_SANITIZE_STRING);
                $emplacementId =filter_input(INPUT_POST,"emplacement",FILTER_VALIDATE_INT);
                $nbPersonnes =filter_input(INPUT_POST,"nbPersonnes",FILTER_VALIDATE_INT);

                $entityManager = $doctrine->getManager();
                // on récupère l'année depuis l'id de la route
                $year = substr($id,0,4);
                // on récupère le round depuis l'id de la route
                $round = substr($id,5,7);
                // on récupère le grand prix grace à l'année et le round
                $grandprix = $doctrine->getRepository(Grandprix::class)->findOneBy(['season' => $year, 'round' => $round],[]);
                // on récupère l'emplacement avec l'id emplacement du formulaire
                $emplacement = $doctrine->getRepository(Emplacement::class)->find($emplacementId);

                // on créer une nouvelle réservation et on lui attribue les différentes données
                $reservation = new Reservation();
                $reservation->setUser($this->getUser());
                $reservation->setGrandprix($grandprix);
                $reservation->setSession($session);
                $reservation->setEmplacement($emplacement);
                $reservation->setNbPersonnes($nbPersonnes);
                $entityManager->persist($reservation);
                $entityManager->flush();
                return $this->redirectToRoute('app_grandprix', ['id' => $id]);
            } else {
                dd('erreur');
                }
        } else {
            return $this->redirectToRoute('app_login');
            }
    }
}