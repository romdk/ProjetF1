<?php

namespace App\Controller;

use App\HttpClient\ActuHttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ActuController extends AbstractController
{
    #[Route('/actu', name: 'app_actu')]
    public function index(ActuHttpClient $actu): Response
    {
        // recupere tous les articles
        $dataArticles = $actu->getArticles();
        $articles = json_decode($dataArticles,true);

        $articlesFav = array();
        
        if($this->getUser()){
            // créer un tableau des favoris de l'utilisateur
            $favoris = array();
            
            // ajoute les pilotes favoris de l'utilisateur au tableau favoris
            $pilotesFav = $this->getUser()->getPilotes();
            foreach ( $pilotesFav as $pilote){
                $dataArticles = $actu->getArticlesByDriver($pilote->getIdApi());
                $articlesPilote = json_decode($dataArticles,true);
                $favoris[] = $articlesPilote;
            }
    
            // ajoute les ecuries favorites de l'utilisateur au tableau favoris
            $ecuriesFav = $this->getUser()->getEcuries();
            foreach ( $ecuriesFav as $ecurie){
                $dataArticles = $actu->getArticlesByConstructor($ecurie->getIdApi());
                $articlesEcurie = json_decode($dataArticles,true);
                $favoris[] = $articlesEcurie; 
            }
            
            // pour chaque favori on ajoute ses articles au tableau d'articles
            foreach($favoris as $favori){
                foreach($favori['hydra:member'] as $article)
                $articlesFav[] = $article;
            }
        }

        return $this->render('actu/index.html.twig', [
            'articles' => $articles,
            'articlesFav' => $articlesFav,
        ]);
    }
}
