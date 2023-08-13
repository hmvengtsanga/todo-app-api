<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class AuthenticationFailureListener
{
    /**
     * @return void
     */
    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
    {
        if ($event->getException() instanceof BadCredentialsException) {
            throw new CustomUserMessageAccountStatusException('Bad credentials.');
        }
    }
}
