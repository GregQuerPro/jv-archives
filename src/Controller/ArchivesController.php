<?php

namespace App\Controller;

use App\Entity\Console;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArchivesController extends AbstractController
{
    #[Route('/console/{slug}', name: 'app_consoles')]
    public function index(Console $console): Response
    {

        return $this->render('archives/console.html.twig', [
            'console' => 'console',
        ]);
    }
}
