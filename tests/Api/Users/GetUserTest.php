<?php

namespace App\Tests\Api\Users;

use App\Tests\Api\AbstractApiTestCase;

class GetUserTest extends AbstractApiTestCase
{
    public function testGetUsersWithAdminRoleSuccess(): void
    {
        $this->authAsUser('joe.developer@test.com', '0000');
        $this->client->request('GET', '/api/users');

        $this->assertResponseStatusCodeSame(200);
    }

    public function testGetUsersWithNoAdminRoleFailed(): void
    {
        $this->authAsUser('joe.developer@test.com', '0000');
        $response = $this->client->request('GET', '/api/users');
        
        $arrayResponse = $response->toArray();
        $this->assertResponseStatusCodeSame(200);

        $data = $arrayResponse['hydra:member'];
        $userEmail = $data[2]['email'];
        $this->authAsUser($userEmail, '0000');
        $this->client->request('GET', '/api/users');
        
        $this->assertResponseStatusCodeSame(403);
    }
}
