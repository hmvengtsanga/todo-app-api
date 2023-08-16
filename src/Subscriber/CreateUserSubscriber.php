<?php

namespace App\Subscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\User;
use App\Message\SendCreationUserMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MessageBusInterface $bus,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => [
                ['addSecureInformations', EventPriorities::PRE_WRITE],
            ],
        ];
    }

    public function addSecureInformations(ViewEvent $event): void
    {
        $user = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (false === ($user instanceof User && Request::METHOD_POST === $method)) {
            return;
        }

        $pwd = $this->generatePlainPassword($user);
        $encodedPassword = $this->passwordHasher->hashPassword(
            $user,
            $pwd,
        );

        $user
           ->setEmail(strtolower($user->getEmail()))
           ->setRoles(['ROLE_USER'])
           ->setPassword($encodedPassword);

        $this->bus->dispatch(
            new SendCreationUserMessage(
                $user->getEmail(),
                $pwd
            )
        );
    }

    private function generatePlainPassword(): string
    {
        $pwd = '';
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; ++$i) {
            $n = rand(0, $alphaLength);
            $pwd .= $alphabet[$n];
        }

        return $pwd;
    }
}
