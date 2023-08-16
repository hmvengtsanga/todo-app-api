<?php

namespace App\Tests\Api\Authentication;

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

    /**
     * @dataProvider invalidGetTokenProvider
     */
    public function testGetTokenFailed(array $parameters, int $expectedStatus): void
    {
        $this->getClient()->request('POST', '/api/authentication', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => $parameters
        ]);

        $this->assertResponseStatusCodeSame($expectedStatus);
    }

    public function invalidGetTokenProvider(): array
    {
        return [
            'Incorrect email' => [
                [
                    'email' => "j.developer@test.com",
                    'password' => "0000",
                ],
                401,
            ],
            'Incorrect password' => [
                [
                    'email' => "joe.developer@test.com",
                    'password' => "0001",
                ],
                401,
            ],
        ];
    }
}
