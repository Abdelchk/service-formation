<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Formateur;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class AdminController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        if ($error) {
            $this->addFlash('error', 'Identifiants incorrects.');
        }

        return $this->render('home/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This should never be reached!');
    }    

    #[Route('/admin', name: 'app_admin')]
    public function load(EntityManagerInterface $manager): Response
    {
        // Restriction d'accès : seuls les administrateurs peuvent accéder
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Récupérer tous les formateurs et clients
        $formateurs = $manager->getRepository(Formateur::class)->findAll();
        $clients = $manager->getRepository(Client::class)->findAll();

        return $this->render('admin/index.html.twig', [
            'formateurs' => $formateurs,
            'clients' => $clients,
        ]);
    }

    #[Route('/admin/delete-formateur/{id}', name: 'delete_formateur', methods: ['POST', 'GET'])]
    public function deleteFormateur(int $id, EntityManagerInterface $manager): Response
    {
        $formateur = $manager->getRepository(Formateur::class)->find($id);

        if ($formateur) {
            $manager->remove($formateur);
            $manager->flush();
            $this->addFlash('success', 'Le formateur a été supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Formateur introuvable.');
        }

        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/delete-client/{id}', name: 'delete_client', methods: ['POST', 'GET'])]
    public function deleteClient(int $id, EntityManagerInterface $manager): Response
    {
        $client = $manager->getRepository(Client::class)->find($id);

        if ($client) {
            $manager->remove($client);
            $manager->flush();
            $this->addFlash('success', 'Le client a été supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Client introuvable.');
        }

        return $this->redirectToRoute('app_admin');
    }
}