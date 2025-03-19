<?php

namespace App\Service;

use App\Interface\UserServiceInterface;
use App\Repository\UserRepository;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;

class UserService implements UserServiceInterface
{
    public function __construct(
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
}