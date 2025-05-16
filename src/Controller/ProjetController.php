<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class ProjetController extends AbstractController
{
    #[Route('/projet', name: 'app_projet')]
    public function index(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_FORMATEUR');
        $projets = $em->getRepository(Projet::class)->findAll();
        return $this->render('projet/index.html.twig', [
            'projets' => $projets,
        ]);
    }

    #[Route('/projet/add', name: 'projet_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_FORMATEUR');

        $projet = new Projet();
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($projet);
            $em->flush();
            $this->addFlash('success', 'Projet ajouté.');
            return $this->redirectToRoute('app_projet');
        }

        return $this->render('projet/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/projet/delete/{id}', name: 'projet_delete', methods: ['POST', 'GET'])]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_FORMATEUR');
        $projet = $em->getRepository(Projet::class)->find($id);
        if ($projet) {
            $em->remove($projet);
            $em->flush();
            $this->addFlash('success', 'Projet supprimé.');
        }
        return $this->redirectToRoute('app_projet');
    }
}