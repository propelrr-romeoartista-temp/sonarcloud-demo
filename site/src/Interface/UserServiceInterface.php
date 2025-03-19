<?php

namespace App\Interface;

use App\Entity\User;
use Pagerfanta\Pagerfanta;

interface UserServiceInterface
{
    public function findPaginated(?string $query, int $page, int $size): Pagerfanta;
    public function create(User $user): User;
    public function update(User $user): User;
}
