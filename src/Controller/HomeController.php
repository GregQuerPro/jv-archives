<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\ConsoleRepository;
use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ArticleRepository $articleRepository, ConsoleRepository $consoleRepository, SerieRepository $serieRepository): Response
    {
        $articles = $articleRepository->findLastArticles();
        $topConsoles = $consoleRepository->findTopConsoles();
        $topSeries = $serieRepository->findTopSeries();

        return $this->render('home/index.html.twig', [
            'articles' => $articles,
            'topConsoles' => $topConsoles,
            'topSeries' => $topSeries
        ]);
    }
}
