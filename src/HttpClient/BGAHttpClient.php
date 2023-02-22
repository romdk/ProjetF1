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
        $response = $this->httpClient->request('GET',"/api/f1/$year", ['verify_peer' => false,]);
        return $response->getContent();
    }




}