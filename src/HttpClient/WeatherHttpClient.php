<?php

namespace App\HttpClient;

use App\Factory\XmlResponseFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




class WeatherHttpClient extends AbstractController
{  
    private $httpClientWeather;

    public function __construct(HttpClientInterface $weather)
    {
        $this->httpClientWeather = $weather;
    }

    public function getWeather($latitude, $longitude, $dateDebut, $dateFin){
        $response = $this->httpClientWeather->request('GET',"/v1/forecast?latitude=$latitude&longitude=$longitude&start_date=$dateDebut&end_date=$dateFin&daily=temperature_2m_max&daily=weathercode&timezone=auto", ['verify_peer'=>false,]);
        return $response->getContent(); 
    }
}