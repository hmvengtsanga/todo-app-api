<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;

abstract class AbstractApiTestCase extends ApiTestCase
{
    protected Client $client;

    public function authAsUser(string $email, string $password)
    {
        $token = self::getToken(self::createClient(), $email, $password);
        $this->setAuthorisationHeaders($token);
    }

    private function getToken(Client $client, string $login, string $password): string
    {
        $response = $client->request('POST', '/api/authentication', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => $login,
                'password' => $password,
            ],
        ]);

        $arrayResponse = $response->toArray();

        return $arrayResponse['token'];
    }

    private function setAuthorisationHeaders(string $token): void
    {
        $this->client = self::createClient(
            [],
            ['headers' => ['authorization' => 'Bearer '.$token]]
        );
    }

    public function createClientWithoutAuth(): void
    {
        $this->client = self::createClient();
    }

    public function checkCreateMessengerMessage(int $expectedNbr = 1): iterable|null
    {
        $transport = self::getContainer()->get('messenger.transport.async');
        $this->assertCount($expectedNbr, $transport->get());

        return $transport->get();
    }
}
