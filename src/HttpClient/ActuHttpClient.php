<?php

namespace App\HttpClient;

use App\Factory\XmlResponseFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




class ActuHttpClient extends AbstractController
{  
    private $httpClientActu;

    public function __construct(HttpClientInterface $actu)
    {
        $this->httpClientActu = $actu;
    }

    public function getArticles(){
        $response = $this->httpClientActu->request('GET',"/api/articles.json", ['verify_peer' => false,]);
        return $response->getContent();
    }

    public function getArticlesByDriver($driver){
        $response = $this->httpClientActu->request('GET',"/api/articles?pilote.idApi=$driver", ['verify_peer' => false,]);
        return $response->getContent();
    }

    public function getArticlesByConstructor($constructor){
        $response = $this->httpClientActu->request('GET',"/api/articles?ecurie.idApi=$constructor", ['verify_peer' => false,]);
        return $response->getContent();
    }
}