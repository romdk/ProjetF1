<?php

namespace App\Controller;

use Stripe\StripeClient;
use App\Entity\Grandprix;
use App\Entity\Emplacement;
use App\Entity\Reservation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'controller_name' => 'StripeController',
        ]);
    }

    #[Route('/{id}/create_checkout_session', name: 'create_checkout_session')]
    public function createCheckoutSession(ManagerRegistry $doctrine, $id): Response
    {
        if($this->getUser()){       
            if(isset($_POST['session']) && isset($_POST['emplacement']) && isset($_POST['nbPersonnes'])){
                $entityManager = $doctrine->getManager();

                // récupère les données du formulaire
                $session =filter_input(INPUT_POST,"session",FILTER_SANITIZE_STRING);
                $emplacementId =filter_input(INPUT_POST,"emplacement",FILTER_VALIDATE_INT);
                $nbPersonnes =filter_input(INPUT_POST,"nbPersonnes",FILTER_VALIDATE_INT);
                $nbJours = $session[0];
                $jourSession = substr($session,2);

                // récupère l'année depuis l'id de la route
                $year = substr($id,0,4);
                //récupère le round depuis l'id de la route
                $round = substr($id,5,7);

                //récupère le grand prix grace à l'année et le round
                $grandprix = $doctrine->getRepository(Grandprix::class)->findOneBy(['season' => $year, 'round' => $round],[]);

                //récupère l'emplacement avec l'id emplacement du formulaire
                $emplacement = $doctrine->getRepository(Emplacement::class)->find($emplacementId);

                //créer une nouvelle réservation et lui attribue les différentes données
                $reservation = new Reservation();
                $reservation->setUser($this->getUser());
                $reservation->setGrandprix($grandprix);
                $reservation->setSession($jourSession);
                $reservation->setEmplacement($emplacement);
                $reservation->setNbPersonnes($nbPersonnes);
                $entityManager->persist($reservation);
                $entityManager->flush();

                $reservationId = $reservation->getId();

                $stripe = new StripeClient($this->getParameter('secret_key'));

                $checkoutSession = $stripe->checkout->sessions->create([
                    'customer_email' => $this->getUser()->getUserIdentifier(),
                    'line_items' => [[
                        'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Session: '.$jourSession.' , Emplacement: '.$emplacement->getNom(),
                        ],
                        'unit_amount' => $emplacement->getPrix()*100*$nbJours,
                        ],
                        'quantity' => $nbPersonnes,
                    ]], 
                    'billing_address_collection' => 'required',
                    'shipping_address_collection' => [
                        'allowed_countries' => ['FR', 'US']
                    ],
                    'mode' => 'payment',
                    'success_url' => 'http://localhost:8000/success/'.$reservationId,
                    'cancel_url' => 'http://localhost:8000/cancel/'.$reservationId,
                    ]);
                return $this->redirect($checkoutSession->url);
            }
        }       
    }

    #[Route('/success/{reservationId}', name: 'stripe_success')]
    public function stripeSuccess(ManagerRegistry $doctrine, $reservationId)
    {
        $reservation = $doctrine->getRepository(Reservation::class)->find($reservationId);
        $grandprix = $reservation->getGrandprix();
        $grandprixId = $grandprix->getSeason().'_'.$grandprix->getRound();
        $statut = 'success';

        $entityManager = $doctrine->getManager();
            $reservation->setStatut($statut);
            $entityManager->flush();
            return $this->render('reservation/success.html.twig');
    }

    #[Route('/cancel/{reservationId}', name: 'stripe_cancel')]
    public function stripeCanceled(ManagerRegistry $doctrine, $reservationId)
    {
        $reservation = $doctrine->getRepository(Reservation::class)->find($reservationId);
        $grandprix = $reservation->getGrandprix();
        $grandprixId = $grandprix->getSeason().'_'.$grandprix->getRound();
        
        $entityManager = $doctrine->getManager();
            $entityManager->remove($reservation);
            $entityManager->flush();
        return $this->redirectToRoute('app_reservation', ['id' => $grandprixId]);
    }

}
