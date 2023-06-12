<?php

namespace App\EventListener;

use App\Entity\Article;
use App\Entity\Console;
use Doctrine\ORM\Event\PreRemoveEventArgs;

class ConsoleListener
{
    public function preRemove(PreRemoveEventArgs $args)
    {
//        $console = $args->getObject();
//
//        $entityManager = $args->getObjectManager();
//
//        // Récupérer tous les articles associés à la série en cours de suppression
//        dd('test');
////        $articles = $entityManager->getRepository(Article::class)->findBy(['consoles' => $console]);
//
//        // Mettre à jour chaque article pour le lier à la série "Autres"
//        foreach ($articles as $article) {
//            $autreConsole = $entityManager->getRepository(Console::class)->findOneBy(['name' => 'Autres']);
//            $article->setConsole($autreConsole);
//            $entityManager->persist($article);
//        }
//
//        $entityManager->flush();
    }
}
