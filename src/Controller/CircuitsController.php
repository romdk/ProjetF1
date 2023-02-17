<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CircuitsController extends AbstractController
{
    #[Route('/circuits', name: 'app_circuits')]
    public function index(): Response
    {
        return $this->render('circuits/index.html.twig', [
            'controller_name' => 'CircuitsController',
        ]);
    }
}
