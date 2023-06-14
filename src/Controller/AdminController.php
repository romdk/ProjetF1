<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Reponse;
use App\Entity\Reservation;
use App\Form\ImageUploadType;
use App\HttpClient\F1HttpClient;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(Security $security, ManagerRegistry $doctrine, Request $request): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            $users = $doctrine->getRepository(User::class)->findAll();
            $posts = $doctrine->getRepository(Post::class)->findAll();
            $reponses = $doctrine->getRepository(Reponse::class)->findAll();

            $form = $this->createForm(ImageUploadType::class);
            $form->handleRequest($request);

            $file = $form->get('image')->getData();
            $target = $form->get('categorie')->getData();

            $circuitName = $form->get('circuitName')->getData();
            $imageCircuitType = $form->get('imageCircuitType')->getData();

            $ecurieName = $form->get('ecurieName')->getData();
            $ecurieImageType = $form->get('ecurieImageType')->getData();

            $grandprixYear = $form->get('grandprixYear')->getData();
            $grandprixRound = $form->get('grandprixRound')->getData();
            $grandprixImageType = $form->get('grandprixImageType')->getData();

            $driverName = $form->get('driverName')->getData();
            $driverImageType = $form->get('driverImageType')->getData();
            $driverNumber = $form->get('driverNumber')->getData();

            $teamName = $form->get('teamName')->getData();

            if ($file && $target && $circuitName && $imageCircuitType) {
                // donne un nouveau nom au fichier
                $newFileName = $circuitName.'_'.$imageCircuitType.'.png';

                // deplace le fichier uploader dans le bon dossier
                try{
                    $file->move(
                        $this->getParameter(name:$target.'_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {

                }
            }
            elseif ($file && $target && $ecurieName && $ecurieImageType) {
                // donne un nouveau nom au fichier
                $newFileName = $ecurieImageType.$ecurieName.'.png';

                // deplace le fichier uploader dans le bon dossier
                try{
                    $file->move(
                        $this->getParameter(name:$target.'_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {

                }
            }
            elseif ($file && $target && $grandprixYear && $grandprixRound && $grandprixImageType) {
                // donne un nouveau nom au fichier
                $newFileName = $grandprixYear.'_'.$grandprixRound.$grandprixImageType.'.png';

                // deplace le fichier uploader dans le bon dossier
                try{
                    $file->move(
                        $this->getParameter(name:$target.'_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {

                }
            }
            elseif ($file && $target && $driverName && $driverImageType) {
                if ($driverNumber) {
                    // donne un nouveau nom au fichier
                    $newFileName = $driverName.$driverNumber.'.png';
                }else {
                    // donne un nouveau nom au fichier
                    $newFileName = $driverName.$driverImageType.'.png';
                }

                // deplace le fichier uploader dans le bon dossier
                try{
                    $file->move(
                        $this->getParameter(name:$target.'_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {

                }
            }
            elseif ($file && $target && $teamName) {
                // donne un nouveau nom au fichier
                $newFileName = $teamName.'_voiture.png';

                // deplace le fichier uploader dans le bon dossier
                try{
                    $file->move(
                        $this->getParameter(name:$target.'_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {

                }
            }
            
            return $this->render('admin/index.html.twig', [
                'users' => $users,
                'posts' => $posts,
                'reponses' => $reponses,
                'formImageUpload' => $form->createView(),
            ]);
        } else {
            return $this->redirectToRoute('app_home');
        }
        
    }

    #[Route('/admin/{id}', name: 'app_admin_messages')]
    public function messages(Security $security, ManagerRegistry $doctrine, Request $request, F1HttpClient $f1, $id): Response
    {
        if ($security->isGranted('ROLE_ADMIN')) {
            $users = $doctrine->getRepository(User::class)->findAll();
            $posts = $doctrine->getRepository(Post::class)->findAll();
            $reponses = $doctrine->getRepository(Reponse::class)->findAll();

            $reservations = $doctrine->getRepository(Reservation::class)->findAll();

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

            $form = $this->createForm(ImageUploadType::class);
            $form->handleRequest($request);

            $file = $form->get('image')->getData();
            $target = $form->get('categorie')->getData();

            $circuitName = $form->get('circuitName')->getData();
            $imageCircuitType = $form->get('imageCircuitType')->getData();

            $ecurieName = $form->get('ecurieName')->getData();
            $ecurieImageType = $form->get('ecurieImageType')->getData();

            $grandprixYear = $form->get('grandprixYear')->getData();
            $grandprixRound = $form->get('grandprixRound')->getData();
            $grandprixImageType = $form->get('grandprixImageType')->getData();

            $driverName = $form->get('driverName')->getData();
            $driverImageType = $form->get('driverImageType')->getData();
            $driverNumber = $form->get('driverNumber')->getData();

            $teamName = $form->get('teamName')->getData();

            if ($file && $target && $circuitName && $imageCircuitType) {
                // donne un nouveau nom au fichier
                $newFileName = $circuitName.'_'.$imageCircuitType.'.png';

                // deplace le fichier uploader dans le bon dossier
                try{
                    $file->move(
                        $this->getParameter(name:$target.'_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {

                }
            }
            elseif ($file && $target && $ecurieName && $ecurieImageType) {
                // donne un nouveau nom au fichier
                $newFileName = $ecurieImageType.$ecurieName.'.png';

                // deplace le fichier uploader dans le bon dossier
                try{
                    $file->move(
                        $this->getParameter(name:$target.'_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {

                }
            }
            elseif ($file && $target && $grandprixYear && $grandprixRound && $grandprixImageType) {
                // donne un nouveau nom au fichier
                $newFileName = $grandprixYear.'_'.$grandprixRound.$grandprixImageType.'.png';

                // deplace le fichier uploader dans le bon dossier
                try{
                    $file->move(
                        $this->getParameter(name:$target.'_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {

                }
            }
            elseif ($file && $target && $driverName && $driverImageType) {
                if ($driverNumber) {
                    // donne un nouveau nom au fichier
                    $newFileName = $driverName.$driverNumber.'.png';
                }else {
                    // donne un nouveau nom au fichier
                    $newFileName = $driverName.$driverImageType.'.png';
                }

                // deplace le fichier uploader dans le bon dossier
                try{
                    $file->move(
                        $this->getParameter(name:$target.'_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {

                }
            }
            elseif ($file && $target && $teamName) {
                // donne un nouveau nom au fichier
                $newFileName = $teamName.'_voiture.png';

                // deplace le fichier uploader dans le bon dossier
                try{
                    $file->move(
                        $this->getParameter(name:$target.'_directory'),
                        $newFileName
                    );
                } catch (FileException $e) {

                }
            }
            
            return $this->render('admin/messages.html.twig', [
                'users' => $users,
                'posts' => $posts,
                'reponses' => $reponses,
                'formImageUpload' => $form->createView(),
                'id' => $id,
                'reservations' => $reservations,
                'grandprix' => $grandprix,
            ]);
        } else {
            return $this->redirectToRoute('app_home');
        }
        
    }

    #[Route('/user/{id}/ban', name: 'ban_user')]
    public function banUser(ManagerRegistry $doctrine, User $user, Security $security)
    { 
        if($security->isGranted('ROLE_ADMIN')){ 
            $entityManager = $doctrine->getManager();

            if($user->isStatut() == 0){
                $user->setStatut(1);
    
                $reponses = $user->getReponses();
                foreach ($reponses as $reponse){
                    $entityManager->remove($reponse);
                }
    
                $posts = $user->getPosts();
                foreach ($posts as $post){
                    $post->setStatut(1);
                    $entityManager->persist($post);
                }
            }
            elseif($user->isStatut() == 1){
                $user->setStatut(0);
            }
            
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_admin');        
        }else {
            return $this->redirectToRoute('app_home');
            }
    }  
}
