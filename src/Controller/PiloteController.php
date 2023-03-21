<?php

namespace App\Controller;

use App\Entity\Pilote;
use App\HttpClient\F1HttpClient;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PiloteController extends AbstractController
{
    #[Route('/pilote/{id}', name: 'app_pilote')]
    public function index(ManagerRegistry $doctrine,F1HttpClient $f1, $id): Response
    {
        $driver = $doctrine->getRepository(Pilote::class)->findOneBy(['idApi' => $id],[]);
        $driverId = $driver->getIdApi();

        $apiData = $f1->getDriverInformations($driverId);
        $driverInformations = json_decode($apiData, true)["MRData"]["DriverTable"]["Drivers"][0];

        $apiData = $f1->getDriverRaces($driverId);
        $driverRaces = json_decode($apiData, true)["MRData"]["RaceTable"]["Races"];

        $apiData = $f1->getDriverSeasons($driverId);
        $driverSeasons = json_decode($apiData, true)["MRData"]["SeasonTable"]["Seasons"];

        $apiData = $f1->getDriverSeasonsWhere1st($driverId);
        $driverSeasonsWhere1st = json_decode($apiData, true)["MRData"]["SeasonTable"]["Seasons"];

        $apiData = $f1->getDriverRacesWhere1st($driverId);
        $driverRacesWhere1st = json_decode($apiData, true)["MRData"]["RaceTable"]["Races"];

        $apiData = $f1->getDriverRacesWhere2nd($driverId);
        $driverRacesWhere2nd = json_decode($apiData, true)["MRData"]["RaceTable"]["Races"];

        $apiData = $f1->getDriverRacesWhere3rd($driverId);
        $driverRacesWhere3rd = json_decode($apiData, true)["MRData"]["RaceTable"]["Races"];

        $nbPodiums = count($driverRacesWhere1st) + count($driverRacesWhere2nd) + count($driverRacesWhere3rd);
        $nbVictoires = count($driverRacesWhere1st);
        $nbTitres = count($driverSeasonsWhere1st);

        return $this->render('pilote/index.html.twig', [
            'id' => $id,
            'driver' => $driver,
            'driverInformations' => $driverInformations,
            'driverRaces' => $driverRaces,
            'driverSeasons' => $driverSeasons,
            'nbPodiums' => $nbPodiums,
            'nbVictoires' => $nbVictoires,
            'nbTitres' => $nbTitres        
        ]);
    }
}
