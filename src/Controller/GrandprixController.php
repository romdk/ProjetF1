<?php

namespace App\Controller;

use App\HttpClient\BGAHttpClient;
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
    public function displayGrandprixDetails(BGAHttpClient $bga, Request $request) {
        $year = $request->request->get('year');
        $round = $request->request->get('round');
        return new Response($bga->getDetailsGrandprix($year, $round));
    }
}
