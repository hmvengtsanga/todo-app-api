<?php

namespace App\Tests\Api\Users;

use App\Tests\Api\AbstractApiTestCase;

class UpdateUserTest extends AbstractApiTestCase
{
    public function testUserUpdateSuccess(): void
    {
        $this->authAsUser('joe.developer@test.com', '0000');
        $this->client->request('PUT', '/api/users/1', [
            'json' => [
                'email' => 'joe.developer@test.com',
                'firstName' => 'John 2',
                'lastName' => 'Doe'
            ]
        ]);
        
        $this->assertResponseStatusCodeSame(200);
    }

    /**
     * @dataProvider invalidUserUpdateProvider
     */
    public function testUserUpdateFailed(array $parameters, int $expectedStatus): void
    {
        $this->authAsUser('joe.developer@test.com', '0000');
        $this->client->request('PUT', '/api/users/1', ['json' => $parameters]);
        $this->assertResponseStatusCodeSame($expectedStatus);
    }

    public function invalidUserUpdateProvider(): array
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
            ]
        ];
    }
}
