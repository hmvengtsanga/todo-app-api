<?php

namespace App\Tests\Api\Users;

use App\Tests\Api\AbstractApiTestCase;

class CreateUserTest extends AbstractApiTestCase
{
    public function testUserCreationSuccess(): void
    {
        $this->createClientWithoutAuth();
        $this->client->request('POST', '/api/users', [
            'json' => [
                'email' => 'john.doe@test.com',
                'firstName' => 'John',
                'lastName' => 'Doe'
            ]
        ]);
        
        $this->assertResponseStatusCodeSame(201);
        $this->checkCreateMessengerMessage();
    }

    /**
     * @dataProvider invalidUserCreationProvider
     */
    public function testUserCreationFailed(array $parameters, int $expectedStatus): void
    {
        $this->createClientWithoutAuth();
        $this->client->request('POST', '/api/users', ['json' => $parameters]);
        $this->assertResponseStatusCodeSame($expectedStatus);
    }

    public function invalidUserCreationProvider(): array
    {
        return [
            'Incorrect email format' => [
                [
                    'email' => 'john.doe.test.com',
                    'firstName' => 'John',
                    'lastName' => 'Doe'
                ],
                422
            ],
            'Blank firstName field' => [
                [
                    'email' => 'john.doe@test.com',
                    'firstName' => '',
                    'lastName' => 'Doe'
                ],
                422
            ],
            'Blank lastName field' => [
                [
                    'email' => 'john.doe@test.com',
                    'firstName' => 'John',
                    'lastName' => ''
                ],
                422
            ],
            'Existing email' => [
                [
                    'email' => 'joe.developer@test.com',
                    'firstName' => 'John',
                    'lastName' => 'Doe'
                ],
                422
            ],
        ];
    }
}
