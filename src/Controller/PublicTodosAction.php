<?php

namespace App\Controller;

use ApiPlatform\Doctrine\Orm\Paginator;
use App\Service\TodoServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class PublicTodosAction extends AbstractController
{
    public function __invoke(Request $request, TodoServiceInterface $todoService): Paginator
    {
        $page = $request->get('page') ?? 1;
        $itemPerPage = $request->get('itemsPerPage') ?? 10;
        $order = $request->get('order') ?? [];

        return $todoService->getPublicTodos(
            $page,
            $itemPerPage,
            $order
        );
    }
}
