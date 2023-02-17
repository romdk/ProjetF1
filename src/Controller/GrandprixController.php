<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GrandprixController extends AbstractController
{
    #[Route('/grandprix', name: 'app_grandprix')]
    public function index(): Response
    {
        return $this->render('grandprix/index.html.twig', [
            'controller_name' => 'GrandprixController',
        ]);
    }
}
