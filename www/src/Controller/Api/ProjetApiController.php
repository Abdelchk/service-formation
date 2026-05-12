<?php

namespace App\Controller\Api;

use App\Entity\AppelDeFonds;
use App\Entity\Projet;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
#[OA\Tag(name: 'Projets')]
class ProjetApiController extends AbstractController
{
    /**
     * Retourne la balance financière d'un projet (lecture seule).
     *
     * La balance est calculée ainsi :
     *   solde = budget_initial + total_appels_de_fonds_reçus - total_factures_émises
     */
    #[Route('/projet/{id}/balance', name: 'projet_balance', methods: ['GET'])]
    #[OA\Get(
        path: '/api/projet/{id}/balance',
        operationId: 'getProjetBalance',
        summary: 'Balance financière d\'un projet',
        security: [['ApiKey' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'Identifiant du projet', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Balance financière du projet',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'projet_id', type: 'integer', example: 1),
                        new OA\Property(property: 'nom', type: 'string', example: 'Formation DevOps'),
                        new OA\Property(property: 'budget_initial', type: 'number', format: 'float', example: 50000.0),
                        new OA\Property(property: 'total_appels_de_fonds', type: 'number', format: 'float', example: 10000.0),
                        new OA\Property(property: 'total_factures', type: 'number', format: 'float', example: 15000.0),
                        new OA\Property(property: 'solde', type: 'number', format: 'float', example: 45000.0),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Clé API manquante ou invalide'),
            new OA\Response(response: 403, description: 'Accès interdit (IP non autorisée)'),
            new OA\Response(response: 404, description: 'Projet non trouvé'),
        ]
    )]
    public function balance(int $id, EntityManagerInterface $em): JsonResponse
    {
        $projet = $em->getRepository(Projet::class)->find($id);

        if (!$projet) {
            return $this->json(['error' => 'Projet non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $totalFactures = 0.0;
        foreach ($projet->getFactures() as $facture) {
            $totalFactures += (float) ($facture->getMontant() ?? 0);
        }

        $totalAppelsDeFonds = 0.0;
        foreach ($projet->getAppelDeFonds() as $appel) {
            $totalAppelsDeFonds += (float) ($appel->getMontant() ?? 0);
        }

        $budgetInitial = (float) ($projet->getBudgetInitial() ?? 0);
        $solde = $budgetInitial + $totalAppelsDeFonds - $totalFactures;

        return $this->json([
            'projet_id'             => $projet->getId(),
            'nom'                   => $projet->getNom(),
            'budget_initial'        => $budgetInitial,
            'total_appels_de_fonds' => $totalAppelsDeFonds,
            'total_factures'        => $totalFactures,
            'solde'                 => $solde,
        ]);
    }

    /**
     * Crée un nouvel appel de fonds pour un projet donné.
     */
    #[Route('/projet/{id}/appel-de-fonds', name: 'projet_create_appel_de_fonds', methods: ['POST'])]
    #[OA\Post(
        path: '/api/projet/{id}/appel-de-fonds',
        operationId: 'createAppelDeFonds',
        summary: 'Créer un appel de fonds pour un projet',
        security: [['ApiKey' => []]],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, description: 'Identifiant du projet', schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['montant'],
                properties: [
                    new OA\Property(property: 'montant', type: 'integer', description: 'Montant en euros (entier positif)', example: 5000),
                    new OA\Property(property: 'date_emission', type: 'string', format: 'date', description: 'Date d\'émission (défaut : aujourd\'hui)', example: '2026-06-01'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Appel de fonds créé avec succès',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 42),
                        new OA\Property(property: 'projet_id', type: 'integer', example: 1),
                        new OA\Property(property: 'montant', type: 'integer', example: 5000),
                        new OA\Property(property: 'date_emission', type: 'string', format: 'date', example: '2026-06-01'),
                    ]
                )
            ),
            new OA\Response(response: 400, description: 'Données invalides (montant manquant ou négatif, date invalide)'),
            new OA\Response(response: 401, description: 'Clé API manquante ou invalide'),
            new OA\Response(response: 403, description: 'Accès interdit (IP non autorisée)'),
            new OA\Response(response: 404, description: 'Projet non trouvé'),
        ]
    )]
    public function createAppelDeFonds(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $projet = $em->getRepository(Projet::class)->find($id);

        if (!$projet) {
            return $this->json(['error' => 'Projet non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (!isset($data['montant']) || !is_numeric($data['montant']) || (int) $data['montant'] <= 0) {
            return $this->json(
                ['error' => 'Le champ "montant" est requis et doit être un entier strictement positif.'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $dateEmission = new \DateTime();
        if (isset($data['date_emission'])) {
            $parsed = \DateTime::createFromFormat('Y-m-d', $data['date_emission']);
            if (!$parsed || $parsed->format('Y-m-d') !== $data['date_emission']) {
                return $this->json(
                    ['error' => 'Format de date invalide. Attendu : YYYY-MM-DD.'],
                    Response::HTTP_BAD_REQUEST
                );
            }
            $dateEmission = $parsed;
        }

        $appel = new AppelDeFonds();
        $appel->setMontant((int) $data['montant']);
        $appel->setIdProjet($projet);
        $appel->setDateEmission($dateEmission);

        $em->persist($appel);
        $em->flush();

        return $this->json([
            'id'            => $appel->getId(),
            'projet_id'     => $projet->getId(),
            'montant'       => $appel->getMontant(),
            'date_emission' => $appel->getDateEmission()->format('Y-m-d'),
        ], Response::HTTP_CREATED);
    }
}
