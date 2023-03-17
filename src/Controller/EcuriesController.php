<?php

namespace App\Controller;

use App\HttpClient\F1HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EcuriesController extends AbstractController
{
    #[Route('/ecuries', name: 'app_ecuries')]
    public function index(F1HttpClient $f1): Response
    {
        $year = date('Y');
        $apiData = $f1->getConstructors($year);
        $constructors = json_decode($apiData,true)["MRData"]["ConstructorTable"]["Constructors"];

        return $this->render('ecuries/index.html.twig', [
            'constructors' => $constructors,
        ]);
    }
}
