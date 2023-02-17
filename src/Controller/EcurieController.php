<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EcurieController extends AbstractController
{
    #[Route('/ecurie', name: 'app_ecurie')]
    public function index(): Response
    {
        return $this->render('ecurie/index.html.twig', [
            'controller_name' => 'EcurieController',
        ]);
    }
}
