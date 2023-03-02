<?php

namespace App\HttpClient;

use App\Factory\XmlResponseFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




class F1HttpClient extends AbstractController
{  
    private $httpClientF1;

    public function __construct(HttpClientInterface $f1)
    {
        $this->httpClientF1 = $f1;
    }

    public function getGrandsprix($year){
        $response = $this->httpClientF1->request('GET',"/api/f1/$year.json", ['verify_peer' => false,]);
        return $response->getContent();
    }

    public function getLastRaceResults(){
        $response = $this->httpClientF1->request('GET',"/api/f1/current/last/results.json", ['verify_peer'=>false,]);
        return $response->getContent();
    }

    public function getDriverStandings($year){
        $response = $this->httpClientF1->request('GET',"/api/f1/$year/driverStandings.json", ['verify_peer'=>false,]);
        return $response->getContent();
    } 

    public function getConstructorStandings($year){
        $response = $this->httpClientF1->request('GET',"/api/f1/$year/constructorStandings.json", ['verify_peer'=>false,]);
        return $response->getContent();
    } 

    public function getDetailsGrandprix($year, $round){
        $response = $this->httpClientF1->request('GET',"/api/f1/$year/$round.json", ['verify_peer'=>false,]);
        return $response->getContent(); 
    }
}