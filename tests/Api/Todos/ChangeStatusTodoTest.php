<?php

namespace App\Tests\Api\Todos;

use App\Tests\Api\AbstractApiTestCase;

class ChangeStatusTodoTest extends AbstractApiTestCase
{
    public function testSetTodoToDoneSuccess(): void
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
            "status" => "done"
        ];

        $response = $this->client->request('PATCH', '/api/todos/'.$todoId.'/status', [
            'headers' => ['content-type' => 'application/merge-patch+json'],
            'json' => [
                "status" => "done"
            ]
        ]);
        $arrayResponse = $response->toArray();

        $this->assertResponseStatusCodeSame(200);
        $this->assertEquals(
            $arrayResponse['status'],
            $data["status"]
        );
    }

    public function testChangeTodoStatusFailedForUnauthenticatedUser(): void
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
        $this->client->request('PATCH', '/api/todos/'.$todoId.'/status', [
            'headers' => ['content-type' => 'application/merge-patch+json'],
            'json' => [
                "status" => "done"
            ]
        ]);
        
        $this->assertResponseStatusCodeSame(401);
    }

    /**
     * @dataProvider invalidChangeTodoStatusProvider
     */
    public function testChangeTodoStatusFailed(array $parameters, int $expectedStatus): void
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

        $this->client->request('PATCH', '/api/todos/'.$todoId.'/status', [
            'headers' => ['content-type' => 'application/merge-patch+json'],
            'json' => $parameters
        ]);

        $this->assertResponseStatusCodeSame($expectedStatus);
    }

    public function invalidChangeTodoStatusProvider(): array
    {
        return [
            'unknow status value' => [
                [
                    "status" => "fait"
                ],
                400
            ],
        ];
    }
}
