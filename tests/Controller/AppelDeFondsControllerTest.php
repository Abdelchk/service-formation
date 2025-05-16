<?php

namespace App\Tests\Controller;

use App\Entity\AppelDeFonds;
use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppelDeFondsControllerTest extends WebTestCase
{
    public function testIndexRequiresLogin()
    {
        $client = static::createClient();
        $client->request('GET', '/appel_de_fonds');
        $this->assertResponseRedirects('/login');
    }

    public function testIndexWithRoleUser()
    {
        $client = static::createClient();
        $userRepo = static::getContainer()->get(ClientRepository::class);
        $user = $userRepo->findOneBy(['email' => 'mc@example.com']);
        $client->loginUser($user);

        $client->request('GET', '/appel_de_fonds');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
    }

    /* public function testAddAppelDeFonds()
    {
        $client = static::createClient();
        $userRepo = static::getContainer()->get(ClientRepository::class);
        $user = $userRepo->findOneBy(['roles' => ['ROLE_USER']]);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/appel_de_fonds/add');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Ajouter')->form([
            // Remplace les champs par ceux de ton formulaire
            'appel_de_fonds_type[dateEmission]' => '2025-01-01',
            'appel_de_fonds_type[datePaiement]' => '2025-01-10',
            'appel_de_fonds_type[montant]' => 1000,
            // 'appel_de_fonds_type[idProjet]' => 1, // si besoin
        ]);
        $client->submit($form);

        $this->assertResponseRedirects('/appel_de_fonds');
        $client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Appel de fonds ajouté');
    } */

    /* public function testDeleteAppelDeFonds()
    {
        $client = static::createClient();
        $userRepo = static::getContainer()->get(UserRepository::class);
        $user = $userRepo->findOneBy(['roles' => ['ROLE_USER']]);
        $client->loginUser($user);

        // Crée un appel de fonds à supprimer
        $em = static::getContainer()->get('doctrine')->getManager();
        $appel = new AppelDeFonds();
        $appel->setDateEmission(new \DateTime('2025-01-01'));
        $appel->setDatePaiement(new \DateTime('2025-01-10'));
        $appel->setMontant(1000);
        $em->persist($appel);
        $em->flush();

        $client->request('GET', '/appel_de_fonds/delete/' . $appel->getId());
        $this->assertResponseRedirects('/appel_de_fonds');
        $client->followRedirect();
        $this->assertSelectorTextContains('.alert-success', 'Appel de fonds supprimé');
    }

    public function testDeleteAppelDeFondsAccessDenied()
    {
        $client = static::createClient();
        // Pas de login
        $client->request('GET', '/appel_de_fonds/delete/1');
        $this->assertResponseRedirects('/login');
    } */
}