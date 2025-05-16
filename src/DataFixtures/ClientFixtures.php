<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Adresse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;


class ClientFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['client']; // Nom du groupe
    }
    public function load(ObjectManager $manager): void
    {
        $client = new Client();
        // $client->setNom("Microsoft");
        // $client->setSiren("123456789");
        // $client->setIban("FR123456789");
        // $client->setComission(10);
        // $client->setPwd(password_hash("password123", PASSWORD_BCRYPT));
        // $client->setEmail("mc@example.com");

        // $adresse = new Adresse();
        // $adresse->setRue("rue de la Paix");
        // $adresse->setNumero("1");
        // $adresse->setVille("Paris");
        // $adresse->setCodePostal("75000");
        // $client->setIdAdresse($adresse);

        $client->setNom("Client Test");
        $client->setSiren("123456789");
        $client->setIban("FR123456789");
        $client->setComission(10);
        $client->setPwd(password_hash("password123", PASSWORD_BCRYPT));
        $client->setEmail("test@example.com");
        $adresse = new Adresse();
        $adresse->setRue("rue du nuage");
        $adresse->setNumero("5");
        $adresse->setVille("Marseille");
        $adresse->setCodePostal("13000");
        $client->setIdAdresse($adresse);

        $manager->persist($client);
        $manager->flush();
    }
}
