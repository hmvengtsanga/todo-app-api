<?php

namespace App\Tests\Api\Todos;

use App\Tests\Api\AbstractApiTestCase;

class GetTodoTest extends AbstractApiTestCase
{
    public function testGetMyTodosSuccess(): void
    {
        $this->authAsUser('joe.developer@test.com', '0000');
        $response = $this->client->request('GET', '/api/todos');

        $arrayResponse = $response->toArray();
        $this->assertResponseStatusCodeSame(200);

        $data = $arrayResponse['hydra:member'];
        $this->assertEquals(
            'Joe',
            $data[0]['owner']['firstName']
        );

        $this->assertEquals(
            'Developer',
            $data[0]['owner']['lastName']
        );
    }

    public function testGetPublicTodosSuccess(): void
    {
        $this->authAsUser('joe.developer@test.com', '0000');
        $response = $this->client->request('GET', '/api/todos/public');
        
        $arrayResponse = $response->toArray();
        $this->assertResponseStatusCodeSame(200);

        $data = $arrayResponse['hydra:member'];
        $this->assertEquals(
            true,
            $data[0]['public']
        );
    }

    public function testGetTodosFailedForUnauthenticatedUser(): void
    {
        $this->createClientWithoutAuth();
        $this->client->request('GET', '/api/todos');
        
        $this->assertResponseStatusCodeSame(401);
    }

    public function testGetPublicTodosFailedForUnauthenticatedUser(): void
    {
        $this->createClientWithoutAuth();
        $this->client->request('GET', '/api/todos/public');
        
        $this->assertResponseStatusCodeSame(401);
    }
}
