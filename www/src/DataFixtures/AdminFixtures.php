<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class AdminFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['final']; // Nom du groupe
    }
    public function load(ObjectManager $manager): void
    {
        $admin = new Admin();
        $admin->setNom("Admin");
        $admin->setEmail("admin@example.com");
        $admin->setPwd(password_hash("admin123", PASSWORD_BCRYPT));
        
        $manager->persist($admin);
        $manager->flush();
    }
}
