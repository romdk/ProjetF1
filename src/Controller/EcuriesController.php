<?php

namespace App\Controller;

use App\Entity\Ecurie;
use App\HttpClient\F1HttpClient;
use Doctrine\Persistence\ManagerRegistry;
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

    #[Route('/ecurie/{id}', name: 'app_ecurie')]
    public function ecurie(F1HttpClient $f1,ManagerRegistry $doctrine, $id): Response
    {

        $year = date('Y');
        $apiData = $f1->getConstructorDrivers($id, $year);
        $constructorDrivers = json_decode($apiData, true)["MRData"]["DriverTable"]["Drivers"];

        $apiData = $f1->getConstructorTitles($id);
        $constructorTitles = json_decode($apiData, true)["MRData"]["StandingsTable"]["StandingsLists"];

        $apiData = $f1->getConstructorSeasons($id);
        $constructorSeasons = json_decode($apiData, true)["MRData"]["SeasonTable"]["Seasons"];

        $apiData = $f1->getConstructorRaces($id);
        $constructorRaces = json_decode($apiData, true)["MRData"]["RaceTable"]["Races"];

        $apiData = $f1->getConstructorWins($id);
        $constructorWins = json_decode($apiData, true)["MRData"]["RaceTable"]["Races"];

        $apiData = $f1->getConstructor2nd($id);
        $constructor2nd = json_decode($apiData, true)["MRData"]["RaceTable"]["Races"];

        $apiData = $f1->getConstructor3rd($id);
        $constructor3rd = json_decode($apiData, true)["MRData"]["RaceTable"]["Races"];

        $constructorPodiums = count($constructor3rd) + count($constructor2nd) + count($constructorWins);

        $apiData = $f1->getConstructorPole($id);
        $constructorPole = json_decode($apiData, true)["MRData"]["RaceTable"]["Races"];

        $ecurie = $doctrine->getRepository(Ecurie::class)->findOneBy(['idApi' => $id],[]);

        return $this->render('ecurie/index.html.twig', [
            'constructorTitles' => $constructorTitles,
            'constructorSeasons' => $constructorSeasons,
            'constructorRaces' => $constructorRaces,
            'constructorWins' => $constructorWins,
            'constructorPodiums' => $constructorPodiums,
            'constructorPole' => $constructorPole,
            'constructorDrivers' => $constructorDrivers,
            'ecurie' => $ecurie,
            'id' => $id,
        ]);
    }
}
