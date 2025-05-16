<?php

namespace App\DataFixtures;

use App\Entity\AppelDeFonds;
use App\Entity\Projet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class AppelDeFondFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['final']; // Nom du groupe
    }
    public function load(ObjectManager $manager): void
    {
        $projet = $manager->getRepository(Projet::class)->findOneBy(['Nom' => 'Projet 1']);

        $appelDeFonds = new AppelDeFonds();
        $appelDeFonds->setDateEmission(new \DateTime('2025-04-15'));
        $appelDeFonds->setMontant(5000);
        $appelDeFonds->setDatePaiement(new \DateTime('2025-04-20'));
        $appelDeFonds->setIdProjet($projet);

        $manager->persist($appelDeFonds);

        $manager->flush();
    }
}