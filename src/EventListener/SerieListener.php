<?php

namespace App\EventListener;

use App\Entity\Article;
use App\Entity\Serie;
use Doctrine\ORM\Event\PreRemoveEventArgs;

class SerieListener
{
    public function preRemove(PreRemoveEventArgs $args)
    {
        $serie = $args->getObject();

        $entityManager = $args->getObjectManager();

        // Récupérer tous les articles associés à la série en cours de suppression
        $articles = $entityManager->getRepository(Article::class)->findBy(['serie' => $serie]);

        // Mettre à jour chaque article pour le lier à la série "Autres"
        foreach ($articles as $article) {
            $autresSerie = $entityManager->getRepository(Serie::class)->findOneBy(['name' => 'Autres']);
            $article->setSerie($autresSerie);
            $entityManager->persist($article);
        }

        $entityManager->flush();
    }
}
