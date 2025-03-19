<?php

namespace App\Service;

use App\Entity\User;
use App\Interface\UserServiceInterface;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository
    )
    {

    }

    public function findPaginated(?string $query, int $page, int $size): Pagerfanta
    {
        return Pagerfanta::createForCurrentPageWithMaxPerPage(
            new QueryAdapter($this->userRepository->findBySearchQueryBuilder(query: $query)),
            $page,
            $size
        );
    }

    public function create(User $user): User
    {
        $user->setRoles(['ROLE_ADMIN']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function update(User $user): User
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}