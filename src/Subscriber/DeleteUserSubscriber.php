<?php

namespace App\Subscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\User;
use App\Message\SendAccountDeletionMessage;
use App\Repository\TodoRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\MessageBusInterface;

class DeleteUserSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MessageBusInterface $bus,
        private TodoRepository $todoRepository
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => [
                ['deleteUserTodos', EventPriorities::POST_VALIDATE],
            ],
        ];
    }

    public function deleteUserTodos(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (false === ($user instanceof User && Request::METHOD_DELETE === $method)) {
            return;
        }

        $todos = $this->todoRepository->findBy([
            'owner' => $user->getId(),
        ]);

        $this->todoRepository->removeTodos($todos);

        $this->bus->dispatch(
            new SendAccountDeletionMessage(
                $user->getEmail()
            )
        );
    }
}
