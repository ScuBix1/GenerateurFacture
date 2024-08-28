<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Entreprise;
use App\Entity\Facture;
use App\Entity\Tache;
use App\Form\ClientType;
use App\Form\EntrepriseType;
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
use App\Service\PdfGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends AbstractController
{
    #[Route(path: "/", name: "home")]
    function index(Request $request, ClientRepository $clientRepository, TacheRepository $tacheRepository, EntityManagerInterface $em): Response
    {
        $clients = $clientRepository->findAll();
        $taches = $tacheRepository->findAll();
        $facture = new Facture();
        $formFacture = $this->createForm(FactureType::class, $facture);
        $formFacture->handleRequest($request);
        if ($formFacture->isSubmitted() && $formFacture->isValid()) {

        }
        return $this->render('home/index.html.twig', [
            "clients" => $clients,
            'taches' => $taches,
            'facture' => $facture,
            'formFacture' => $formFacture,
        ]);
    }

    #[Route(path: "/client-info/{id}", name: "client.get")]
    function getClient($id, ClientRepository $clientRepository): JsonResponse
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
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($client);
            $em->flush();
            $this->addFlash('success', 'Le client est bien ajouté');
            return $this->redirectToRoute('home');
        }
        return $this->render('client/create.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route(path: '/client/{id}/edit', name: 'client.edit', methods: ['GET', 'POST'])]
    public function editClient(Client $client, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Les informations du client ont bien été mis à jour');
            return $this->redirectToRoute('home');
        }
        return $this->render('client/edit.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route(path: '/client/{id}/delete', name: 'client.delete', methods: ['DELETE'])]
    public function deleteClient(Client $client, EntityManagerInterface $em)
    {
        $em->remove($client);
        $em->flush();
        return $this->redirectToRoute('home');
    }

    #[Route(path: "/entreprise-info/{id}", name: "entreprise.get")]
    function getEntreprise($id, EntrepriseRepository $entrepriseRepository): JsonResponse
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
    #[Route(path: "/entreprise/create", name: "entreprise.create")]
    function createEntreprise(Request $request, EntityManagerInterface $em)
    {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($entreprise);
            $em->flush();
            $this->addFlash('success', 'L\'entreprise est bien ajouté');
            return $this->redirectToRoute('home');
        }
        return $this->render('entreprise/create.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route(path: '/entreprise/{id}/edit', name: 'entreprise.edit', methods: ['GET', 'POST'])]
    public function editEntreprise(Entreprise $entreprise, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Les informations de votre entreprise ont bien été mis à jour');
            return $this->redirectToRoute('home');
        }
        return $this->render('entreprise/edit.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route(path: '/entreprise/{id}/delete', name: 'entreprise.delete', methods: ['DELETE'])]
    public function deleteEntreprise(Entreprise $entreprise, EntityManagerInterface $em)
    {
        $em->remove($entreprise);
        $em->flush();
        return $this->redirectToRoute('home');
    }


    #[Route(path: '/task/create', name: 'task.create')]
    public function create(Request $request, EntityManagerInterface $em)
    {
        $tache = new Tache();
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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
    public function editTask(Tache $tache, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'La tâche a bien été mis à jour');
            return $this->redirectToRoute('home');
        }
        return $this->render('task/edit.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route(path: '/task/{id}/delete', name: 'task.delete', methods: ['DELETE'])]
    public function deleteTask(Tache $tache, EntityManagerInterface $em)
    {
        $em->remove($tache);
        $em->flush();
        $this->addFlash('success', 'La tâche a bien été supprimé !');
        return $this->redirectToRoute('home');
    }
    #[Route(path: '/pdf-output/{clientId}/{entrepriseId}', name: 'pdf.create')]
    public function output($clientId, $entrepriseId, PdfGeneratorService $pdfGeneratorService, ClientRepository $clientRepository, EntrepriseRepository $entrepriseRepository, TacheRepository $tacheRepository): Response{
        $entreprise = $entrepriseRepository->find($entrepriseId);
        $client = $clientRepository->find($clientId);  
        $taches = $tacheRepository->findAll();
        $html = $this->renderView('home/pdf.html.twig',[
            'client' => $client,
            'entreprise' => $entreprise,
            'taches' => $taches
        ]);
        $content = $pdfGeneratorService->getPdf($html);
        return new Response($content, 200, [
            'Content-Type' => 'application/pdf'
        ]);
    }
}
