<?php

namespace App\Tests\Api\Todos;

use App\Tests\Api\AbstractApiTestCase;

class CreateTodoTest extends AbstractApiTestCase
{
    public function testTodoCreationSuccess(): void
    {
        $this->authAsUser('joe.developer@test.com', '0000');
        $this->client->request('POST', '/api/todos', [
            'json' => [
                'title' => 'I have to test my app.',
                'description' => 'I have to test my app.',
                'public' => false
            ]
        ]);
        
        $this->assertResponseStatusCodeSame(201);
    }

    public function testTodoCreationFailedForUnauthenticatedUser(): void
    {
        $this->createClientWithoutAuth();
        $this->client->request('POST', '/api/todos', [
            'json' => [
                'title' => 'I have to test my app.',
                'description' => 'I have to test my app.',
                'public' => false
            ]
        ]);
        
        $this->assertResponseStatusCodeSame(401);
    }

    /**
     * @dataProvider invalidTodoCreationProvider
     */
    public function testTodoCreationFailed(array $parameters, int $expectedStatus): void
    {
        $this->authAsUser('joe.developer@test.com', '0000');
        $this->client->request('POST', '/api/todos', ['json' => $parameters]);
        $this->assertResponseStatusCodeSame($expectedStatus);
    }

    public function invalidTodoCreationProvider(): array
    {
        return [
            'Blank title field' => [
                [
                    'title' => '',
                    'description' => 'I have to test my app.',
                    'public' => false
                ],
                422
            ]
        ];
    }
}
