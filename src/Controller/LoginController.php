<?php

namespace App\Controller;

use App\Repository\ConsoleRepository;
use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{

    public function __construct(
        private ConsoleRepository $consoleRepository,
        private SerieRepository   $serieRepository
    )
    {
    }

    #[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        $topConsoles = $this->consoleRepository->findTopConsoles();
        $topSeries = $this->serieRepository->findTopSeries();
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'topConsoles' => $topConsoles,
            'topSeries' => $topSeries
        ]);
    }
}
