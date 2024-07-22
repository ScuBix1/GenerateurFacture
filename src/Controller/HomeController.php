<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Entity\Tache;
use App\Form\FactureType;
use App\Repository\ClientRepository;
use App\Repository\FactureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\TacheType;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController{
    #[Route(path: "/", name: "home")]
    function index(Request $request, ClientRepository $clientRepository, TacheRepository $tacheRepository, EntityManagerInterface $em): Response{
        $clients = $clientRepository->findAll();
        $taches = $tacheRepository->findAll();
        $facture = new Facture();
        $formFacture = $this->createForm(FactureType::class, $facture);
        $formFacture->handleRequest($request);
        if($formFacture->isSubmitted() && $formFacture->isValid()){
            $em->persist($facture);
            $em->flush();
            $this->addFlash('success', 'La facture est bien ajouté');
        }
        return $this->render('home/index.html.twig', [
            "clients" => $clients,
            'taches' => $taches,
            'facture' => $facture,
            'formFacture' => $formFacture,
        ]);
    }

    #[Route(path: '/task/create', name: 'task.create')]
    public function create(Request $request, EntityManagerInterface $em){
        $tache = new Tache();
        $form = $this->createForm(TacheType::class);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($tache);
            $em->flush();
            $this->addFlash('success', 'La tâches est bien ajouté');
        }
        return $this->render('task/create.html.twig', [
            'form' => $form,
        ]);
    }   
}
