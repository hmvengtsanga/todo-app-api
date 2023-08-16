<?php

namespace App\MessageHandler;

use App\Message\SendAccountDeletionMessage;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Address;

#[AsMessageHandler]
final class AccountDeletionMessageHandler
{
    public function __construct(
        private MailerInterface $mailer
    ) {
    }

    public function __invoke(SendAccountDeletionMessage $message)
    {
        $email = (new TemplatedEmail())
            ->from('no-reply@todo-app.com')
            ->to(new Address($message->getEmail()))
            ->subject('Goodbye!')
            ->htmlTemplate('emails/user/deletion.html.twig')
            ->context([
                'userEmail' => $message->getEmail(),
            ])
        ;

        $this->mailer->send($email);
    }
}
