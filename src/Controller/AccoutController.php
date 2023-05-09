<?php

namespace App\Controller;

use App\Repository\ConsoleRepository;
use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{

    public function __construct(
        private ConsoleRepository $consoleRepository,
        private SerieRepository   $serieRepository,
    )
    {
    }

    #[Route('/compte', name: 'app_account')]
    public function consoles(Security $security): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $user = $security->getUser();
        $topConsoles = $this->consoleRepository->findTopConsoles();
        $topSeries = $this->serieRepository->findTopSeries();

        return $this->render('archives/consoles.html.twig', [
            'user' => $user,
            'topConsoles' => $topConsoles,
            'topSeries' => $topSeries
        ]);
    }
}
