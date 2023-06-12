<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
use App\Repository\ConsoleRepository;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class BlogController extends AbstractController
{

    public function __construct(
        private ConsoleRepository  $consoleRepository,
        private SerieRepository    $serieRepository,
        private PaginatorInterface $paginator,
        private Security           $security
    )
    {
    }

    #[Route('/blog', name: 'app_blog')]
    public function blog(ArticleRepository $articleRepository, Request $request): Response
    {

        $topConsoles = $this->consoleRepository->findTopConsoles();
        $topSeries = $this->serieRepository->findTopSeries();
        $articles = $articleRepository->findAll();
//        dd($articles)


        $pagination = $this->paginator->paginate(
            $articles, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            12 /*limit per page*/
        );

        return $this->render('blog/index.html.twig', [
            'topConsoles' => $topConsoles,
            'topSeries' => $topSeries,
            'articles' => $pagination
        ]);
    }

    #[Route('/blog/{slug}', name: 'app_article')]
    public function article(Article $article, ArticleRepository $articleRepository, Request $request, $slug, EntityManagerInterface $em, CommentaireRepository $commentaireRepository): Response
    {
        $topConsoles = $this->consoleRepository->findTopConsoles();
        $topSeries = $this->serieRepository->findTopSeries();
        $similarArticles = $articleRepository->findOtherArticlesBySerie($article->getSerie());

        $commentQuery = $commentaireRepository->findCommentsByArticle($article);

        $comments = $this->paginator->paginate(
            $commentQuery, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            12 /*limit per page*/
        );


        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment
                ->setArticle($article)
                ->setAuthor($this->getUser());
            $commentaireRepository->save($comment, true);
            return $this->redirectToRoute('app_article', ['slug' => $slug], Response::HTTP_SEE_OTHER);
        }


        return $this->render('article/index.html.twig', [
            'topConsoles' => $topConsoles,
            'topSeries' => $topSeries,
            'article' => $article,
            'comments' => $comments,
            'similarArticles' => $similarArticles,
            'form' => $form
        ]);
    }

    #[Route('/comment/delete/{id}', name: 'app_delete_comment', methods: ['POST'])]
    public function deleteComment(Request $request, Comment $comment, CommentaireRepository $commentRepository): Response
    {
        $article = $comment->getArticle();
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $commentRepository->remove($comment, true);
        }
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/api/article/last', name: 'app_article_last')]
    public function lastArticle(ArticleRepository $articleRepository, SerializerInterface $serializer): JsonResponse
    {
        [$lastArticle] = $articleRepository->findLastArticle();
        $values = [
            'title' => $lastArticle->getTitle(),
            'content' => $lastArticle->getContent()
        ];
        $json = json_encode($values);
        return new JsonResponse($json, JsonResponse::HTTP_OK, [], true);
    }
}
