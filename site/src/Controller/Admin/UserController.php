<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Interface\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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

    #[Route('/admin/users/new', name: 'app_admin_users_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $user = new User();
        $form  = $this->createUserForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->create($user);
            $this->addFlash('success', 'User created');

            return $this->redirectToRoute('app_admin_users', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/users/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/admin/users/{id}/edit', name: 'app_admin_users_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user): Response
    {
        $form  = $this->createUserForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->update($user);
            $this->addFlash('success', 'User ' . $user->getUsername() . ' updated');

            return $this->redirectToRoute('app_admin_users', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/users/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    private function createUserForm(User $user = null): FormInterface
    {
        $user = $user ?? new User();

        return $this->createForm(UserType::class, $user, [
            'action' => $user->getId() ?
                $this->generateUrl('app_admin_users_edit', ['id' => $user->getId()])
                : $this->generateUrl('app_admin_users_new'),
        ]);
    }
}
