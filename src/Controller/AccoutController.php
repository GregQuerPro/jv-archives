<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\User;
use App\Form\NewPasswordType;
use App\Form\PasswordType;
use App\Form\ProfilType;
use App\Form\UserType;
use App\Repository\CommentaireRepository;
use App\Repository\ConsoleRepository;
use App\Repository\SerieRepository;
use App\Repository\UserRepository;
use App\Services\Slugifier;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class AccoutController extends AbstractController
{

    public function __construct(
        private ConsoleRepository $consoleRepository,
        private SerieRepository   $serieRepository,
    )
    {
    }

    #[Route('/compte', name: 'app_account')]
    public function account(Request $request, Security $security, CommentaireRepository $commentRespository, PaginatorInterface $paginator): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $user = $security->getUser();
        $topConsoles = $this->consoleRepository->findTopConsoles();
        $topSeries = $this->serieRepository->findTopSeries();

        if (!$request->query->get('duration') || $request->query->get('duration') === 'all-time') {
            $commentsQuery = $commentRespository->findAllLastComments($user);
        } else {
            $duration = $request->query->get('duration');
            $commentsQuery = $commentRespository->findLastCommentsByDuration($user, $duration);
        }

        $comments = $paginator->paginate(
            $commentsQuery, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4 /*limit per page*/
        );

        return $this->render('accout/accout.html.twig', [
            'user' => $user,
            'comments' => $comments,
            'topConsoles' => $topConsoles,
            'topSeries' => $topSeries
        ]);
    }

    #[Route('/compte/edit', name: 'app_account_edit')]
    public function edit(Security $security, Request $request, UserPasswordHasherInterface $userPasswordHasher, Slugifier $slugifier, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        if (!$security->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $user = $security->getUser();
        $topConsoles = $this->consoleRepository->findTopConsoles();
        $topSeries = $this->serieRepository->findTopSeries();

        $formProfil = $this->createForm(ProfilType::class, $user, [
            'validation_groups' => ['profil'],
        ]);
        $formNewPassword = $this->createForm(NewPasswordType::class, $user, [
            'validation_groups' => ['new_password'],
        ]);

        $formProfil->handleRequest($request);
        if ($formProfil->isSubmitted() && $formProfil->isValid()) {
            $slugifier->slugifyUserName($user);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Les nouvelles infos de votre profil ont bien été modifié'
            );

            return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
        }

        $formNewPassword->handleRequest($request);
        if ($formNewPassword->isSubmitted() && $formNewPassword->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $user->getPassword()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre nouveau mot de passe a bien été enregistré'
            );

            return $this->redirectToRoute('app_account', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('accout/edit.html.twig', [
            'user' => $user,
            'topConsoles' => $topConsoles,
            'topSeries' => $topSeries,
            'formProfil' => $formProfil,
            'formNewPassword' => $formNewPassword
        ]);
    }

    #[Route('/compte/delete/{id}', name: 'app_account_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository, Security $security, SessionInterface $session, $id): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $currentUserId = $security->getUser()->getId();
            if ($currentUserId == $id) {
                $session = new Session();
                $session->invalidate();
            }
            $userRepository->remove($user, true);

        }

        $this->addFlash(
            'danger',
            'Votre compte a bien été supprimé !'
        );
        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
