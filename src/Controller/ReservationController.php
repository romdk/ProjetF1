<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/reservation/{id}', name: 'app_reservation')]
    public function index(Request $request): Response
    {
        $id = $request->attributes->get('_route_params');
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'route_param' => $id
        ]);
    }
}
