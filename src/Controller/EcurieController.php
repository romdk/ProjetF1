<?php

namespace App\Controller;

use App\Entity\Ecurie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EcurieController extends AbstractController
{
    #[Route('/ecurie', name: 'app_ecurie')]
    public function index(): Response
    {
        return $this->render('ecurie/index.html.twig', [
            'controller_name' => 'EcurieController',
        ]);
    }

    #[Route('/fav/ecurie/{id}', name: 'fav_ecurie')]
    public function favEcurie(Ecurie $ecurie, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        if($ecurie->isFavByUser($user)) {
            $ecurie->removeUser($user);
            $manager->flush();
            return $this->json(['fav' => count($ecurie->getUsers()) ]);
        }

        $ecurie->addUser($user);
        $manager->flush();
        return $this->json(['fav' => count($ecurie->getUsers()) ]);
    }
}
