<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Entity\Console;
use App\Entity\Serie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {

        $series = [];
        for ($serieIndex = 0; $serieIndex <= 50; $serieIndex++) {
            $serie = new Serie();
            $serie
                ->setName("Série $serieIndex")
                ->setSlug("serie-$serieIndex")
                ->setMetaTitle("MetaTitle Série $serieIndex")
                ->setMetaDescription("MetaDescription Série $serieIndex")
                ->setImageName('testing.jpg');
            $manager->persist($serie);
            $series[] = $serie;
        }
        $manager->flush();

        $consoles = [];
        for ($consoleIndex = 0; $consoleIndex <= 50; $consoleIndex++) {
            $console = new Console();
            $console
                ->setName("Console $consoleIndex")
                ->setSlug("console-$consoleIndex")
                ->setMetaTitle("MetaTitle Console $serieIndex")
                ->setMetaDescription("MetaDescription Console $serieIndex")
                ->setImageName('testing.jpg');
            $manager->persist($console);
            $consoles[] = $console;
        }
        $manager->flush();

        $users = [];

        $adminUser = new User();
        $hashedPassword = $this->passwordHasher->hashPassword(
            $adminUser,
            "password"
        );
        $adminUser
            ->setUsername('Greg Quer')
            ->setPassword($hashedPassword)
            ->setEmail("gregquer@gmail.com")
            ->setSlug("greg-quer")
            ->setRoles(["ROLE_USER", "ROLE_ADMIN"]);
        $manager->persist($adminUser);
        $users[] = $adminUser;

        for ($userIndex = 0; $userIndex <= 2; $userIndex++) {
            $user = new User();
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                "password$userIndex"
            );
            $user
                ->setUsername("Username $userIndex")
                ->setSlug("username-$userIndex")
                ->setPassword($hashedPassword)
                ->setEmail("user$userIndex@gmail.com")
                ->setRoles(["ROLE_USER"]);
            $manager->persist($user);
            $users[] = $user;
        }
        $manager->flush();

        $articles = [];
        for ($articleIndex = 0; $articleIndex <= 500; $articleIndex++) {
            $published = $articleIndex % 2 === 0 ? true : false;
            $article = new Article();
            $article
                ->setTitle("Article $articleIndex")
                ->setSlug("article-$articleIndex")
                ->setMetaTitle("MetaTitle Article $serieIndex")
                ->setMetaDescription("MetaDescription Article $serieIndex")
                ->setContent("Contenu Article $articleIndex")
                ->setPublished($published)
                ->setSerie($series[array_rand($series)])
                ->addConsole($consoles[array_rand($consoles)])
                ->setAuthor($users[array_rand($users)])
                ->setImageName('testing.jpg');
            $manager->persist($article);
            $articles[] = $article;
        }
        $manager->flush();

        $comments = [];
        for ($commentIndex = 0; $commentIndex <= 5000; $commentIndex++) {
            $comment = new Commentaire();
            $comment
                ->setContent("Contenu Commentaire $commentIndex")
                ->setAuthor($users[array_rand($users)])
                ->setArticle($articles[array_rand($articles)]);
            $manager->persist($comment);
            $comments[] = $comment;
        }
        $manager->flush();

    }
}
