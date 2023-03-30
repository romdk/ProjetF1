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
        $dataArticles = $actu->getArticles();
        $articles = json_decode($dataArticles,true);
        // dd($articles);
        return $this->render('actu/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}
