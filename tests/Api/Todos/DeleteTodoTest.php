<?php

namespace App\Tests\Api\Todos;

use App\Tests\Api\AbstractApiTestCase;

class DeleteTodoTest extends AbstractApiTestCase
{
    public function testDeleteTodoSuccess(): void
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

        $this->client->request('DELETE', '/api/todos/'.$todoId, [
            'json' => []
        ]);

        $this->assertResponseStatusCodeSame(204);
    }

    public function testDeleteTodoFailedForUnauthenticatedUser(): void
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

        $this->createClientWithoutAuth();
        $this->client->request('DELETE', '/api/todos/'.$todoId, [
            'json' => []
        ]);
        
        $this->assertResponseStatusCodeSame(401);
    }
}
