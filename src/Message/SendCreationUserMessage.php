<?php

namespace App\Message;

final class SendCreationUserMessage
{
    private string $email;
    private string $plainPassword;

    public function __construct(string $email, string $plainPassword)
    {
        $this->email = $email;
        $this->plainPassword = $plainPassword;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}
