<?php

namespace App\Service;

use ApiPlatform\Doctrine\Orm\Paginator;

interface TodoServiceInterface
{
    public function getPublicTodos(int $page, int $itemPerPage, array $order): Paginator;
}
