<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    public const USER_UPDATE = 'USER_UPDATE';
    public const USER_DELETE = 'USER_DELETE';
    public const USER_READ = 'USER_READ';

    public function __construct(private Security $security)
    {
    }

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [
                self::USER_UPDATE,
                self::USER_DELETE,
                self::USER_READ,
                ])
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::USER_UPDATE:
            case self::USER_DELETE:
            case self::USER_READ:
                return $this->canAct($subject, $user);
        }

        return false;
    }

    private function canAct(User $subject, UserInterface $user)
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return $user->getId() === $subject->getId();
    }
}
