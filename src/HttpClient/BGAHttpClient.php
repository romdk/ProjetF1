<?php

namespace App\HttpClient;

use App\Factory\XmlResponseFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/** 
 * Class BGAHttpClient 
 * @package App\Client 
 */

class BGAHttpClient extends AbstractController
{
    /**
     * @var HttpClientInterface
     */
    
    private $httpClient;

    /**
     * BGAHttpClient constructor.
     * @param HttpClientInterface $bga
     */

    public function __construct(HttpClientInterface $bga)
    {
        $this->httpClient = $bga;
    }

    public function getGrandsprix($year){
        $response = $this->httpClient->request('GET',"/api/f1/$year.json", ['verify_peer' => false,]);
        return $response->getContent();
    }

    public function getLastRaceResults(){
        $response = $this->httpClient->request('GET',"/api/f1/current/last/results.json", ['verify_peer'=>false,]);
        return $response->getContent();
    }

    public function getDriverStandings($year){
        $response = $this->httpClient->request('GET',"/api/f1/$year/driverStandings.json", ['verify_peer'=>false,]);
        return $response->getContent();
    } 

    public function getConstructorStandings($year){
        $response = $this->httpClient->request('GET',"/api/f1/$year/constructorStandings.json", ['verify_peer'=>false,]);
        return $response->getContent();
    } 

    public function getDetailsGrandprix($year, $round){
        $response = $this->httpClient->request('GET',"/api/f1/$year/$round.json", ['verify_peer'=>false,]);
        return $response->getContent(); 
    }

    public function getWeather($latitude, $longitude){
        $response = $this->httpClient->request('GET',"https://weatherbit-v1-mashape.p.rapidapi.com/forecast/daily?lat=$latitude&lon=$longitude&units=metric&lang=fr", ['verify_peer'=>false,]);
        return $response->getContent(); 
    }




}