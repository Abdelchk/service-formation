<?php

namespace App\DataFixtures;

use App\Entity\Facture;
use App\Entity\Projet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class FactureFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['final']; // Nom du groupe
    }
    public function load(ObjectManager $manager): void
    {
        $facture = new Facture();
        $facture->setDateFacture(new \DateTime('2025-04-01'));
        $facture->setDatePaiement(new \DateTime('2025-04-10'));
        $facture->setMontant(1500.50);
        $facture->setProjet($manager->getRepository(Projet::class)->findOneBy(['Nom' => 'Projet 1']));

        $manager->persist($facture);

        $manager->flush();
    }
}