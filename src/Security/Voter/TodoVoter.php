<?php

namespace App\Security\Voter;

use App\Entity\Todo;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TodoVoter extends Voter
{
    public const TODO_UPDATE = 'TODO_UPDATE';
    public const TODO_DELETE = 'TODO_DELETE';
    public const TODO_READ = 'TODO_READ';
    public const TODO_CHANGE_STATUS = 'TODO_CHANGE_STATUS';

    public function __construct(private Security $security)
    {
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [
                self::TODO_UPDATE,
                self::TODO_DELETE,
                self::TODO_READ,
                self::TODO_CHANGE_STATUS,
                ])
            && $subject instanceof Todo;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::TODO_CHANGE_STATUS:
                return $this->canChangeStatus($subject, $user);
            case self::TODO_DELETE:
                return $this->canDelete($subject, $user);
            case self::TODO_UPDATE:
                return $this->canUpdate($subject, $user);
            case self::TODO_READ:
                return $this->canRead($subject, $user);
        }

        return false;
    }

    private function canRead(Todo $subject, UserInterface $user)
    {
        return $subject->getOwner()->getId() === $user->getId() || true === $subject->isPublic();
    }

    private function canChangeStatus(Todo $subject, UserInterface $user)
    {
        return $subject->getOwner()->getId() === $user->getId();
    }

    private function canDelete(Todo $subject, UserInterface $user)
    {
        return $subject->getOwner()->getId() === $user->getId();
    }

    private function canUpdate(Todo $subject, UserInterface $user)
    {
        return $subject->getOwner()->getId() === $user->getId();
    }
}
