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
        header('Access-Control-Allow-Origin: *');
        return $this->render('offline/console.html.twig');
    }
}
