<?php

namespace App\DataFixtures;

use App\Entity\Formation;
use App\Entity\Formateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class FormationFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['final']; // Nom du groupe
    }
    public function load(ObjectManager $manager): void
    {
        $formation = new Formation();
        $formation->setLibelle("Formation Symfony");
        $formation->setCoutHt(500.0);
        $formation->setTauxTva(20);
        $formation->setDateformation(new \DateTime('2025-01-01'));
        $formation->setFormateur($manager->getRepository(Formateur::class)->findOneBy(['Nom' => 'Dupont']));

        $manager->persist($formation);

        $manager->flush();
    }
}