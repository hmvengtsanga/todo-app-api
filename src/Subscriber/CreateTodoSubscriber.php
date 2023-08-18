<?php

namespace App\Subscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Todo;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CreateTodoSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => [
                ['setTodoOwner', EventPriorities::PRE_WRITE],
            ],
        ];
    }

    public function setTodoOwner(ViewEvent $event): void
    {
        $todo = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (false === ($todo instanceof Todo && Request::METHOD_POST === $method)) {
            return;
        }

        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $todo
           ->setOwner($currentUser)
        ;
    }
}
