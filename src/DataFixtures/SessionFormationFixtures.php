<?php

namespace App\DataFixtures;

use App\Entity\SessionFormation;
use App\Entity\Formation;
use App\Entity\Projet;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class SessionFormationFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['final']; // Nom du groupe
    }
    public function load(ObjectManager $manager): void
    {
        $formation = $manager->getRepository(Formation::class)->findOneBy(['libelle' => 'Formation Symfony']);
        $projet = $manager->getRepository(Projet::class)->findOneBy(['Nom' => 'Projet 1']);

        $sessionFormation = new SessionFormation();
        $sessionFormation->setDateDebut(new \DateTime('2025-05-01'));
        $sessionFormation->setDateFin(new \DateTime('2025-05-05'));
        $sessionFormation->setCout(2000);
        $sessionFormation->setIdFormation($formation);
        $sessionFormation->setIdProjet($projet);

        $manager->persist($sessionFormation);

        $manager->flush();
    }
}