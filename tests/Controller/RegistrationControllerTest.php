<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{

    private $client;

    protected function setUp(): void
    {
        $client = static::createClient();
;       $this->client = $client;
    }

    public function testRegistration()
    {
        // Effectuer une requête GET sur la page d'inscription
        $crawler = $this->client->request('GET', '/inscription');

        // Vérifier que la page d'inscription est accessible (code HTTP 200)
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Remplir le formulaire d'inscription avec des données valides
        $form = $crawler->selectButton('Valider')->form();
        $form['registration_form[email]'] = 'test@example.com';
        $form['registration_form[username]'] = 'usernametest';
        $form['registration_form[password]'] = 'password123';
        $crawler = $this->client->submit($form);

        // Vérifier que l'utilisateur est redirigé vers la page de succès après l'inscription
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('/login', $this->client->getResponse()->headers->get('location'));

        // Vérifier que l'utilisateur a été enregistré dans la base de données
        $em = $this->client->getContainer()->get('doctrine')->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['email' => 'test@example.com']);
        $this->assertNotNull($user);
        $this->assertEquals('test@example.com', $user->getEmail());
    }

    protected function tearDown(): void
    {
        $em = $this->client->getContainer()->get('doctrine')->getManager();
        // Supprimer l'utilisateur créé dans le test
        $user = $em->getRepository(User::class)->findOneBy(['email' => 'test@example.com']);
        if ($user) {
            $em->remove($user);
            $em->flush();
        }
        parent::tearDown();
    }
}

