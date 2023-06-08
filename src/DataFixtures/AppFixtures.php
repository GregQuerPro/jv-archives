<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Commentaire;
use App\Entity\Console;
use App\Entity\Media;
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

//        $medias = [];
//        for ($mediaIndex = 0; $mediaIndex <= 3; $mediaIndex++) {
//            $media = new Media();
//            $media
//                ->setType('image')
//                ->setImageAlt('photo profil utilisateur')
//                ->setImageName('mario.jpg');
//            $manager->persist($media);
//            $medias[] = $media;
//        }
//        $manager->flush();

        $series = [];
        $seriesDisplayNumber = 0;
        for ($serieIndex = 0; $serieIndex <= 50; $serieIndex++) {
            $serie = new Serie();
            $serie
                ->setName("Série $serieIndex")
                ->setSlug("serie-$serieIndex")
                ->setMetaTitle("MetaTitle Série $serieIndex")
                ->setMetaDescription("Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur delectus esse, nisi numquam officiis pariatur Série $serieIndex")
                ->setImageName('testing.jpg');

            if ($seriesDisplayNumber < 4) {
                $serie->setDisplay(true);
                $seriesDisplayNumber++;
            }
            $manager->persist($serie);
            $series[] = $serie;
        }
        $manager->flush();

        $cons = [
            [
                "name" => "PC",
                "slug" => "pc",
                "color" => "#000000"
            ],
            [
                "name" => "Switch",
                "slug" => "switch",
                "color" => "#A40404"
            ],
            [
                "name" => "XSX",
                "slug" => "xbox sx",
                "color" => "#007E14"
            ],
            [
                "name" => "PS5",
                "slug" => "ps5",
                "color" => "#0034BB"
            ],
        ];
        $consoles = [];
        $consolesDisplayNumber = 0;
        for ($consoleIndex = 0; $consoleIndex < count($cons); $consoleIndex++) {
            $console = new Console();
//            dump($cons[$consoleIndex]["name"]);
            $console
                ->setName($cons[$consoleIndex]["name"])
                ->setSlug($cons[$consoleIndex]["slug"])
                ->setColor($cons[$consoleIndex]["color"])
                ->setMetaTitle("MetaTitle Console $serieIndex")
                ->setMetaDescription("Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur delectus esse, nisi numquam officiis Console $serieIndex")
                ->setImageName('testing.jpg');

            if ($consolesDisplayNumber < 4) {
                $console->setDisplay(true);
                $consolesDisplayNumber++;
            }
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
            ->setRoles(["ROLE_USER", "ROLE_ADMIN"])
            ->setIsVerified(true)
            ->setImageName('mario.jpg')
        ;
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
                ->setRoles(["ROLE_USER"])
                ->setIsVerified(true)
                ->setImageName('mario.jpg');
            $manager->persist($user);
            $users[] = $user;
        }
        $manager->flush();

        $articles = [];
        for ($articleIndex = 0; $articleIndex <= 500; $articleIndex++) {


            $published = $articleIndex % 2 === 0 ? true : false;
            $article = new Article();
            $article
                ->setTitle("Resident Evil : Un remake qui rend hommage au chef d'oeuvre $articleIndex")
                ->setSlug("resident-evil-un-remake-qui-rend-hommage-$articleIndex")
                ->setMetaTitle("MetaTitle Article $serieIndex")
                ->setMetaDescription("Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur delectus esse, nisi numquam officiis Article $serieIndex")
                ->setContent("<p>Lorem ipsum dolor sit amet. Ut magni necessitatibus in maxime veniam est molestias labore ab officia quibusdam et rerum quasi est ullam dolores ut odit illum. Qui molestiae obcaecati qui iste itaque sit quas rerum. Aut consequatur odit non minus distinctio nam quaerat sunt sed aliquam esse aut quas dicta eum nisi placeat. Ea assumenda expedita ea nobis nobis quo iusto blanditiis ea dolores neque ut ipsam consequuntur et debitis maiores aut quia animi. Id corporis officia ut suscipit reiciendis ut iste deserunt et repellat incidunt sit magni accusantium est galisum expedita.</p>
    <h2>Shadow of the Colossus : Un remake qui rend hommage au chef d'oeuvre</h2>
    <p>Lorem ipsum dolor sit amet. Ut magni necessitatibus in maxime veniam est molestias labore ab officia quibusdam et rerum quasi est ullam dolores ut odit illum. Qui molestiae obcaecati qui iste itaque sit quas rerum.
    Aut consequatur odit non minus distinctio nam quaerat sunt sed aliquam esse aut quas dicta eum nisi placeat. Ea assumenda expedita ea nobis nobis quo iusto blanditiis ea dolores neque ut ipsam consequuntur et debitis maiores aut quia animi. Id corporis officia ut suscipit reiciendis ut iste deserunt et repellat incidunt sit magni accusantium est galisum expedita.</p>
    <p>Lorem ipsum dolor sit amet. Ut magni necessitatibus in maxime veniam est molestias labore ab officia quibusdam et rerum quasi est ullam dolores ut odit illum. Qui molestiae obcaecati qui iste itaque sit quas rerum.
    Aut consequatur odit non minus distinctio nam quaerat sunt sed aliquam esse aut quas dicta eum nisi placeat. Ea assumenda expedita ea nobis nobis quo iusto blanditiis ea dolores neque ut ipsam consequuntur et debitis maiores aut quia animi. Id corporis officia ut suscipit reiciendis ut iste deserunt et repellat incidunt sit magni accusantium est galisum expedita.</p>
    <h2>Shadow of the Colossus : Un remake qui rend hommage au chef d'oeuvre</h2>
    <p>Lorem ipsum dolor sit amet. Ut magni necessitatibus in maxime veniam est molestias labore ab officia quibusdam et rerum quasi est ullam dolores ut odit illum. Qui molestiae obcaecati qui iste itaque sit quas rerum.
    Aut consequatur odit non minus distinctio nam quaerat sunt sed aliquam esse aut quas dicta eum nisi placeat. Ea assumenda expedita ea nobis nobis quo iusto blanditiis ea dolores neque ut ipsam consequuntur et debitis maiores aut quia animi. Id corporis officia ut suscipit reiciendis ut iste deserunt et repellat incidunt sit magni accusantium est galisum expedita.</p>
    <p>Lorem ipsum dolor sit amet. Ut magni necessitatibus in maxime veniam est molestias labore ab officia quibusdam et rerum quasi est ullam dolores ut odit illum. Qui molestiae obcaecati qui iste itaque sit quas rerum.
    Aut consequatur odit non minus distinctio nam quaerat sunt sed aliquam esse aut quas dicta eum nisi placeat. Ea assumenda expedita ea nobis nobis quo iusto blanditiis ea dolores neque ut ipsam consequuntur et debitis maiores aut quia animi. Id corporis officia ut suscipit reiciendis ut iste deserunt et repellat incidunt sit magni accusantium est galisum expedita.</p>
    <h2>Shadow of the Colossus : Un remake qui rend hommage au chef d'oeuvre</h2>
    <p>Lorem ipsum dolor sit amet. Ut magni necessitatibus in maxime veniam est molestias labore ab officia quibusdam et rerum quasi est ullam dolores ut odit illum. Qui molestiae obcaecati qui iste itaque sit quas rerum.
    Aut consequatur odit non minus distinctio nam quaerat sunt sed aliquam esse aut quas dicta eum nisi placeat. Ea assumenda expedita ea nobis nobis quo iusto blanditiis ea dolores neque ut ipsam consequuntur et debitis maiores aut quia animi. Id corporis officia ut suscipit reiciendis ut iste deserunt et repellat incidunt sit magni accusantium est galisum expedita.</p>")
                ->setPublished($published)
                ->setSerie($series[array_rand($series)])
                ->addConsole($consoles[array_rand($consoles)])
                ->setAuthor($users[array_rand($users)])
                ->setImageName(rand(1, 12) . '.jpg');

            $random = rand(0, 4);
            for ($i = 0; $i < $random; $i++) {
                $article->addConsole($consoles[array_rand($consoles)]);
            }

            $manager->persist($article);
            $articles[] = $article;
        }
        $manager->flush();

        $comments = [];
        for ($commentIndex = 0; $commentIndex <= 5000; $commentIndex++) {
            $comment = new Commentaire();
            $comment
                ->setContent("Contenu Commentaire $commentIndex Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur delectus esse, nisi numquam officiis Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur delectus esse, nisi numquam officiis Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur delectus esse, nisi numquam officiis Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur delectus esse, nisi numquam officiis")
                ->setAuthor($users[array_rand($users)])
                ->setArticle($articles[array_rand($articles)]);
            $manager->persist($comment);
            $comments[] = $comment;
        }
        $manager->flush();

    }
}
