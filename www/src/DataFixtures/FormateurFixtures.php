<?php

namespace App\DataFixtures;

use App\Entity\Formateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;


class FormateurFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['formateur']; // Nom du groupe
    }
    public function load(ObjectManager $manager): void
    {
        $formateur = new Formateur();
        $formateur->setNom("Dupont");
        $formateur->setPrenom("Jean");
        $formateur->setEmail("jean.dupont@example.com");
        $formateur->setPwd(password_hash("password123", PASSWORD_BCRYPT));

        $formateur2 = new Formateur();
        $formateur2->setNom("Martin");
        $formateur2->setPrenom("Marie");
        $formateur2->setEmail("marie.martin@example.com");
        $formateur2->setPwd(password_hash("password123", PASSWORD_BCRYPT));

        $manager->persist($formateur);
        $manager->persist($formateur2);
        $manager->flush();
    }
}
