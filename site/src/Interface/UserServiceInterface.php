<?php

namespace App\Interface;

use Pagerfanta\Pagerfanta;

interface UserServiceInterface
{
    public function findPaginated(?string $query, int $page, int $size): Pagerfanta;
}
