<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\HttpClient\F1HttpClient;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfilController extends AbstractController
{
    #[Route('/profil/{id}', name: 'app_profil')]
    public function index(F1HttpClient $f1, ManagerRegistry $doctrine, User $user, Request $request, SluggerInterface $slugger, $id): Response
    {
        $currUserId = $this->getUser()->getId();
        if($currUserId != $id){
            return $this->redirectToRoute('app_profil', ['id' => $currUserId]);
            // dd($this->getUser()->getId());
        }
        // on récupère les reservations de l'utilisateur connecté
        $reservations = $this->getUser()->getReservations();
        // on créer un tableau associatif
        $grandprix = array();
        // pour chaque réservation
        foreach($reservations as $reservation){
            // on récupère la saison et le round du grandprix de la réservation
            $year = $reservation->getGrandprix()->getSeason();
            $round = $reservation->getGrandprix()->getRound();
            // puis on récupère les données du grandprix depuis l'api
            $data = $f1->getDetailsGrandprix($year, $round);
            // on remplis le tableau associatif avec comme clé l'id de la réservation et comme valeur le nom du grandprix
            $grandprix[$reservation->getId()] = ["raceName" => json_decode($data,true)["MRData"]["RaceTable"]["Races"]["0"]["raceName"], "date" =>json_decode($data,true)["MRData"]["RaceTable"]["Races"]["0"]["date"]];
        }

        $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            $file = $form->get('image')->getData();

            if ($file) {
                // recupere le nom du fichier original
                $originalFileName = pathinfo($file->getClientOriginalName(), flags:PATHINFO_FILENAME);
                // trasforme le nom du fichier en une string qui contient que des caracteres ASCII
                $safeFileName = $slugger->slug($originalFileName);
                // ajout un id unique au fichier pour eviter les doublons
                $newFileName = $safeFileName.'-'.uniqid().'.'.$file->guessExtension();

                // deplace le fichier uploader dans le bon dossier
                try{
                    $file->move(
                        $this->getParameter(name:'user_directory'),
                        $newFileName
                    );
                } catch (FileExeption $e) {

                }

                $user->setImage($newFileName);
            }

            if($form->isSubmitted() && $form->isValid())
            {
                $user = $form->getData();
                $entityManager = $doctrine->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute('app_profil', ['id' => $user->getId()]);
            }

        return $this->render('profil/index.html.twig', [
            'reservations' => $reservations,
            'grandprix' => $grandprix,
            'formEditUser' => $form->createView()
        ]);
    }
}   
