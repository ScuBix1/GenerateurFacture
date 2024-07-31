<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Facture;
use App\Entity\Tache;
use App\Form\ClientType;
use App\Form\FactureType;
use App\Repository\ClientRepository;
use App\Repository\FactureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\TacheType;
use App\Repository\EntrepriseRepository;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends AbstractController{
    #[Route(path: "/", name: "home")]
    function index(Request $request, ClientRepository $clientRepository, TacheRepository $tacheRepository, EntityManagerInterface $em): Response{
        $clients = $clientRepository->findAll();
        $taches = $tacheRepository->findAll();
        $facture = new Facture();
        $formFacture = $this->createForm(FactureType::class, $facture);
        $formFacture->handleRequest($request);
        if($formFacture->isSubmitted() && $formFacture->isValid()){
        }
        return $this->render('home/index.html.twig', [
            "clients" => $clients,
            'taches' => $taches,
            'facture' => $facture,
            'formFacture' => $formFacture,
        ]);
    }

    #[Route(path: "/client-info/{id}", name: "client.get")]
    function getClient($id, ClientRepository $clientRepository):  JsonResponse
    {
        $client = $clientRepository->find($id);

        if (!$client) {
            return new JsonResponse(['error' => 'Client non trouvé'], 404);
        }

        return new JsonResponse([
            'nom' => $client->getNomSociete(),
            'adresse' => $client->getAdresse(),
            'telephone' => $client->getTelephone(),
            'email' => $client->getEmail()
        ]);
    }
    
    #[Route(path: "/client/create", name: "client.create")]
    function createClient(Request $request, EntityManagerInterface $em)
    {
            $client = new Client();
            $form = $this->createForm(ClientType::class, $client);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $em->persist($client);
                $em->flush();
                $this->addFlash('success', 'Le client est bien ajouté');
                return $this->redirectToRoute('home');
            }
            return $this->render('client/create.html.twig', [
                'form' => $form,
            ]);
    }

    #[Route(path: "/entreprise-info/{id}", name: "entreprise.get")]
    function getEntreprise($id, EntrepriseRepository $entrepriseRepository):  JsonResponse
    {
        $entreprise = $entrepriseRepository->find($id);

        if (!$entreprise) {
            return new JsonResponse(['error' => 'Entreprise non trouvé'], 404);
        }

        return new JsonResponse([
            'nom' => $entreprise->getNomSociete(),
            'adresse' => $entreprise->getAdresse(),
            'telephone' => $entreprise->getTelephone(),
            'email' => $entreprise->getEmail(),
            'siren' => $entreprise->getSiren(),
        ]);
    }

    #[Route(path: '/task/create', name: 'task.create')]
    public function create(Request $request, EntityManagerInterface $em){
        $tache = new Tache();
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($tache);
            $em->flush();
            $this->addFlash('success', 'La tâches est bien ajouté');
            return $this->redirectToRoute('home');
        }
        return $this->render('task/create.html.twig', [
            'form' => $form,
        ]);
    }   
    #[Route(path: '/task/{id}/edit', name: 'task.edit', methods: ['GET', 'POST'])]
    public function editTask(Tache $tache, Request $request, EntityManagerInterface $em){
       $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success', 'La tâche a bien été mis à jour');
            return $this->redirectToRoute('home');
        }
        return $this->render('task/edit.html.twig', [
            'form' => $form, 
        ]);
    }  
    #[Route(path: '/task/{id}', name: 'task.delete', methods: ['DELETE'])]
    public function deleteTask(Tache $tache, EntityManagerInterface $em){
        $em->remove($tache);
        $em->flush();
        $this->addFlash('success', 'La tâche a bien été supprimé !');
        return $this->redirectToRoute('home');
    }    
}
