<?php

namespace App\MessageHandler;

use App\Message\SendCreationUserMessage;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Address;

#[AsMessageHandler]
final class CreationUserMessageHandler
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    public function __invoke(SendCreationUserMessage $message)
    {
        $email = (new TemplatedEmail())
            ->from('no-reply@todo-app.com')
            ->to(new Address($message->getEmail()))
            ->subject('Welcome to TODO APP!')
            ->htmlTemplate('emails/user/signup.html.twig')
            ->context([
                'userEmail' => $message->getEmail(),
                'userPwd' => $message->getPlainPassword(),
            ])
        ;

        $this->mailer->send($email);
    }
}
