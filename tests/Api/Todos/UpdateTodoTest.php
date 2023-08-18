<?php

namespace App\Tests\Api\Todos;

use App\Tests\Api\AbstractApiTestCase;

class UpdateTodoTest extends AbstractApiTestCase
{
    public function testTodoUpdateSuccess(): void
    {
        $this->authAsUser('joe.developer@test.com', '0000');
        $responseCreation = $this->client->request('POST', '/api/todos', [
            'json' => [
                'title' => 'I have to test my app.',
                'description' => 'I have to test my app.',
                'public' => false
            ]
        ]);
        $arrayResponse = $responseCreation->toArray();
        
        $this->assertResponseStatusCodeSame(201);
        
        $todoId = $arrayResponse['id'];
        $data = [
            'title' => 'I have to test my app and deploy.',
            'description' => 'I have to test my app and deploy.',
            'public' => true
        ];

        $responseUpdate = $this->client->request('PUT', '/api/todos/'.$todoId, [
            'json' => $data
        ]);
        $arrayResponse = $responseUpdate->toArray();

        $this->assertResponseStatusCodeSame(200);
        $this->assertEquals(
            $data['title'],
            $arrayResponse['title']
        );
        $this->assertEquals(
            $data['description'],
            $arrayResponse['description']
        );
    }

    public function testTodoUpdateFailedForUnauthenticatedUser(): void
    {
        $this->authAsUser('joe.developer@test.com', '0000');
        $responseCreation = $this->client->request('POST', '/api/todos', [
            'json' => [
                'title' => 'I have to test my app.',
                'description' => 'I have to test my app.',
                'public' => false
            ]
        ]);
        $arrayResponse = $responseCreation->toArray();
        
        $this->assertResponseStatusCodeSame(201);
        
        $todoId = $arrayResponse['id'];
        $data = [
            'title' => 'I have to test my app and deploy.',
            'description' => 'I have to test my app and deploy.',
            'public' => true
        ];

        $this->createClientWithoutAuth();
        $this->client->request('PUT', '/api/todos/'.$todoId, [
            'json' => $data
        ]);
        
        $this->assertResponseStatusCodeSame(401);
    }

    /**
     * @dataProvider invalidTodoUpdateProvider
     */
    public function testUpdateTodoFailed(array $parameters, int $expectedStatus): void
    {
        $this->authAsUser('joe.developer@test.com', '0000');
        $responseCreation = $this->client->request('POST', '/api/todos', [
            'json' => [
                'title' => 'I have to test my app.',
                'description' => 'I have to test my app.',
                'public' => false
            ]
        ]);
        $arrayResponse = $responseCreation->toArray();
        
        $this->assertResponseStatusCodeSame(201);
        
        $todoId = $arrayResponse['id'];

        $this->client->request('PUT', '/api/todos/'.$todoId, [
            'json' => $parameters
        ]);

        $this->assertResponseStatusCodeSame($expectedStatus);
    }

    public function invalidTodoUpdateProvider(): array
    {
        return [
            'Blank title field' => [
                [
                    'title' => '',
                    'description' => 'I have to test my app.',
                    'public' => false
                ],
                422
            ],
        ];
    }
}
