<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OfflineController extends AbstractController
{
    #[Route('/offline', name: 'app_offline')]
    public function index(): Response
    {
        return $this->render('offline/console.html.twig');
    }
}
