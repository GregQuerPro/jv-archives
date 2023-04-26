<?php

namespace App\Controller\Admin;

use App\Entity\Console;
use App\Form\ConsoleType;
use App\Repository\ConsoleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/console')]
class ConsoleController extends AbstractController
{
    #[Route('/', name: 'app_admin_console_index', methods: ['GET'])]
    public function index(ConsoleRepository $consoleRepository, PaginatorInterface $paginator, Request $request): Response
    {

        $consoles = $consoleRepository->findAll();

        $pagination = $paginator->paginate(
        $consoles, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
        10 /*limit per page*/
    );

        return $this->render('admin/console/index.html.twig', [
            'consoles' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_admin_console_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ConsoleRepository $consoleRepository): Response
    {
        $console = new Console();
        $form = $this->createForm(ConsoleType::class, $console);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $consoleRepository->save($console, true);

            return $this->redirectToRoute('app_admin_console_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/console/new.html.twig', [
            'console' => $console,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_console_show', methods: ['GET'])]
    public function show(Console $console): Response
    {
        return $this->render('admin/console/show.html.twig', [
            'console' => $console,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_console_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Console $console, ConsoleRepository $consoleRepository): Response
    {
        $form = $this->createForm(ConsoleType::class, $console);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $consoleRepository->save($console, true);
        }

        return $this->renderForm('admin/console/edit.html.twig', [
            'console' => $console,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_console_delete', methods: ['POST'])]
    public function delete(Request $request, Console $console, ConsoleRepository $consoleRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$console->getId(), $request->request->get('_token'))) {
            $consoleRepository->remove($console, true);
        }

        return $this->redirectToRoute('app_admin_console_index', [], Response::HTTP_SEE_OTHER);
    }
}
