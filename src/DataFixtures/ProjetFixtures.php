<?php

namespace App\DataFixtures;

use App\Entity\Projet;
use App\Entity\Client;
use App\Entity\Formateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class ProjetFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['projet']; // Nom du groupe
    }
    public function load(ObjectManager $manager): void
    {
        $projet = new Projet();
        $projet->setNom("Projet 1");
        $projet->setBudgetInitial(10000);
        $projet->setSeuilAlerte(9000);
        $client = $manager->getRepository(Client::class)->findOneBy(['nom' => 'Microsoft']);
        if (!$client) {
            throw new \Exception('Client non trouvé. Ajoute des fixtures pour Client.');
        }
        $projet->setIdClient($client);

        $formateur = $manager->getRepository(Formateur::class)->findOneBy(['Nom' => 'Dupont']); // Récupère un formateur existant
        if (!$formateur) {
            throw new \Exception('Formateur non trouvé. Ajoute des fixtures pour Formateur.');
        }
        $projet->setFormateurReferent($formateur);

        $manager->persist($projet);
        $manager->flush();
    }
}
