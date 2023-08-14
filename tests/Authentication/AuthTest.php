<?php

namespace App\Tests\Authentication;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;

final class AuthTest extends ApiTestCase
{
    private function getClient(): Client
    {
        return self::createClient();
    }

    public function testGetTokenSuccess(): void
    {
        $response = $this->getClient()->request('POST', '/api/authentication', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => "joe.developer@test.com",
                'password' => "0000",
            ],
        ]);

        $arrayResponse = $response->toArray();
        
        $this->assertResponseStatusCodeSame(200);
        $this->assertNotNull($arrayResponse['token']);
    }

    public function testGetTokenFailed(): void
    {
        $this->getClient()->request('POST', '/api/authentication', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => "j.developer@test.com",
                'password' => "0000",
            ],
        ]);

        $this->assertResponseStatusCodeSame(401);
    }
}
