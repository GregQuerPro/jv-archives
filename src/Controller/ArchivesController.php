<?php

namespace App\Controller;

use App\Entity\Console;
use App\Entity\Serie;
use App\Repository\ArticleRepository;
use App\Repository\ConsoleRepository;
use App\Repository\SerieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArchivesController extends AbstractController
{

    public function __construct(
        private ConsoleRepository  $consoleRepository,
        private SerieRepository    $serieRepository,
        private PaginatorInterface $paginator
    )
    {
    }

    #[Route('/consoles', name: 'app_consoles')]
    public function consoles(): Response
    {
        $topConsoles = $this->consoleRepository->findTopConsoles();
        $topSeries = $this->serieRepository->findTopSeries();
        $consoles = $this->consoleRepository->findConsolesInAlphaOrder();

        return $this->render('archives/consoles.html.twig', [
            'consoles' => $consoles,
            'topConsoles' => $topConsoles,
            'topSeries' => $topSeries
        ]);
    }

    #[Route('/console/{slug}', name: 'app_console')]
    public function console(Console $console, ArticleRepository $articleRepository, Request $request): Response
    {
        $topConsoles = $this->consoleRepository->findTopConsoles();
        $topSeries = $this->serieRepository->findTopSeries();
        $articles = $articleRepository->findByConsole($console);

        $pagination = $this->paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            12 /*limit per page*/
        );

        return $this->render('archives/console.html.twig', [
            'console' => $console,
            'topConsoles' => $topConsoles,
            'topSeries' => $topSeries,
            'articles' => $pagination
        ]);
    }

    #[Route('/series', name: 'app_series')]
    public function series(): Response
    {
        $topConsoles = $this->consoleRepository->findTopConsoles();
        $topSeries = $this->serieRepository->findTopSeries();
        $series = $this->serieRepository->findSeriesInAlphaOrder();


        return $this->render('archives/series.html.twig', [
            'series' => $series,
            'topConsoles' => $topConsoles,
            'topSeries' => $topSeries
        ]);
    }

    #[Route('/serie/{slug}', name: 'app_serie')]
    public function serie(Serie $serie, ArticleRepository $articleRepository, Request $request): Response
    {
        $topConsoles = $this->consoleRepository->findTopConsoles();
        $topSeries = $this->serieRepository->findTopSeries();
        $articles = $articleRepository->findBy([
            'serie' => $serie,
        ]);;

        $pagination = $this->paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            12 /*limit per page*/
        );

        return $this->render('archives/serie.html.twig', [
            'serie' => $serie,
            'topConsoles' => $topConsoles,
            'topSeries' => $topSeries,
            'articles' => $pagination
        ]);
    }
}
