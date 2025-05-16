<?php

namespace App\Controller;

use App\Entity\SessionFormation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class SessionFormationController extends AbstractController
{
    #[Route('/session_formation', name: 'app_session_formation')]
    public function index(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_FORMATEUR');
        $sessions = $em->getRepository(SessionFormation::class)->findAll();
        return $this->render('session_formation/index.html.twig', [
            'sessions' => $sessions,
        ]);
    }

    #[Route('/session_formation/add', name: 'session_formation_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_FORMATEUR');
        // ... logique d'ajout ...
        $session = new SessionFormation();
        // $session->set... (remplir selon le formulaire)
        $em->persist($session);
        $em->flush();
        $this->addFlash('success', 'Session de formation ajoutée.');
        return $this->redirectToRoute('app_session_formation');
    }

    #[Route('/session_formation/delete/{id}', name: 'session_formation_delete', methods: ['POST', 'GET'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_FORMATEUR');
        $session = $em->getRepository(SessionFormation::class)->find($id);
        if ($session) {
            $em->remove($session);
            $em->flush();
            $this->addFlash('success', 'Session de formation supprimée.');
        }
        return $this->redirectToRoute('app_session_formation');
    }
}