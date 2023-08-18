<?php

namespace App\Service;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Repository\TodoRepository;

class TodoService implements TodoServiceInterface
{
    public function __construct(
        private TodoRepository $todoRepository
    ) {
    }

    public function getPublicTodos(int $page, int $itemPerPage, array $order): Paginator
    {
        return $this->todoRepository->getPublicTodosPaginator(
            $page,
            $itemPerPage,
            $order
        );
    }
}
