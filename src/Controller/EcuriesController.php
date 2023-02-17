<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EcuriesController extends AbstractController
{
    #[Route('/ecuries', name: 'app_ecuries')]
    public function index(): Response
    {
        return $this->render('ecuries/index.html.twig', [
            'controller_name' => 'EcuriesController',
        ]);
    }
}
