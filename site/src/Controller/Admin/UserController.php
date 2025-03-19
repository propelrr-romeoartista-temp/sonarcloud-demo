<?php

namespace App\Controller\Admin;

use App\Interface\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    public function __construct(
        private readonly UserServiceInterface $userService
    )
    {

    }

    #[Route('/admin/users', name: 'app_admin_users')]
    public function index(
        #[MapQueryParameter] int $page = 1,
        #[MapQueryParameter] string $query = null
    ): Response
    {
        $pager = $this->userService->findPaginated($query, $page, 10);
        
        return $this->render('admin/users/index.html.twig', [
            'users' => $pager,
        ]);
    }

    #[Route('/new', name: 'app_admin_users_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        PmoServiceInterface $pmoService
    ): Response
    {
        $pmo = new Pmo();
        $form  = $this->createPmoForm($pmo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pmoService->create($pmo);
            $this->addFlash('success', 'PMO Created');

            if ($request->headers->has('turbo-frame')) {
                $stream = $this->renderBlockView('admin/pages/pmo-directory/new.html.twig', 'stream_success', [
                    'pmo' => $pmo
                ]);

                $this->addFlash('stream', $stream);
            }

            return $this->redirectToRoute('app_admin_pmo_directory_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/pages/pmo-directory/new.html.twig', [
            'pmo' => $pmo,
            'form' => $form,
        ]);
    }
}
