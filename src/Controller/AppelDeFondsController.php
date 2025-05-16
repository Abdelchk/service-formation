<?php

namespace App\Controller;

use App\Entity\AppelDeFonds;
use App\Entity\Projet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class AppelDeFondsController extends AbstractController
{
    #[Route('/appel_de_fonds', name: 'app_appel_de_fonds')]
    public function index(EntityManagerInterface $em): Response
    {
        $appels = $em->getRepository(AppelDeFonds::class)->findAll();
        return $this->render('appel_de_fonds/index.html.twig', [
            'appels' => $appels,
        ]);
    }

    #[Route('/appel_de_fonds/add', name: 'appel_de_fonds_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');
        // ... logique d'ajout (formulaire, persist, flush) ...
        // Exemple rapide :
        $appel = new AppelDeFonds();
        // $appel->set... (remplir selon le formulaire)
        $em->persist($appel);
        $em->flush();
        $this->addFlash('success', 'Appel de fonds ajouté.');
        return $this->redirectToRoute('app_appel_de_fonds');
    }

    #[Route('/appel_de_fonds/delete/{id}', name: 'appel_de_fonds_delete', methods: ['POST', 'GET'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $appel = $em->getRepository(AppelDeFonds::class)->find($id);
        if (!$appel) {
            $this->addFlash('error', 'Appel de fonds introuvable.');
            return $this->redirectToRoute('app_appel_de_fonds');
        }
        // Seul le client ou l'admin peut supprimer
        if (!$this->isGranted('ROLE_CLIENT') && !$this->isGranted('ROLE_ADMIN')) {
            throw $this->createAccessDeniedException();
        }
        $em->remove($appel);
        $em->flush();
        $this->addFlash('success', 'Appel de fonds supprimé.');
        return $this->redirectToRoute('app_appel_de_fonds');
    }
}