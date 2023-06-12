<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{

    private $em;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->em = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testCreatedAt()
    {
        // Créer une instance de l'utilisateur
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setUsername('test_user');

        // Persister l'utilisateur
        $this->em->persist($user);

        // Vérifier que la valeur de createdAt a été définie
        $createdAt = $user->getCreatedAt();
        $this->assertInstanceOf(\DateTimeImmutable::class, $createdAt);

    }
}
