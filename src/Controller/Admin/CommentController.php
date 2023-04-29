<?php

namespace App\Controller\Admin;

use App\Entity\Commentaire;
use App\Entity\User;
use App\Form\CommentType;
use App\Repository\CommentaireRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/comment')]
class CommentController extends AbstractController
{

    private User|null $currentUser = null;

    public function __construct(Security $security)
    {
        $this->currentUser = $security->getUser();
    }

    #[Route('/', name: 'app_admin_comment_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $comments = $commentRepository->findAll();

        $pagination = $paginator->paginate(
            $comments, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('admin/comment/index.html.twig', [
            'comments' => $pagination,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_comment_show', methods: ['GET'])]
    public function show(Commentaire $comment): Response
    {
        return $this->render('admin/comment/show.html.twig', [
            'comment' => $comment,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_comment_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $comment, CommentaireRepository $commentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $commentRepository->remove($comment, true);
        }

        $this->addFlash(
            'danger',
            'Votre contenu a bien été supprimé !'
        );
        return $this->redirectToRoute('app_admin_comment_index', [], Response::HTTP_SEE_OTHER);
    }
}
