<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model;
use ApiPlatform\OpenApi\OpenApi;

final class AuthenticationDecorator implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $decorated
    ) {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $schemas = $openApi->getComponents()->getSchemas();

        // Auth token
        $schemas['Token'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'token' => [
                    'type' => 'string',
                    'readOnly' => true,
                    'example' => 'eyJ0eXAiOiJ.....',
                ],
                'refreshToken' => [
                    'type' => 'string',
                    'readOnly' => true,
                    'example' => 'c8d060d9cb.....',
                ],
                'lastname' => [
                    'type' => 'string',
                    'readOnly' => true,
                ],
                'firstname' => [
                    'type' => 'string',
                    'readOnly' => true,
                ]
            ],
        ]);
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'email' => [
                    'type' => 'string',
                    'example' => 'joe.dev@test.com',
                ],
                'password' => [
                    'type' => 'string',
                    'example' => '0000',
                ],
            ],
        ]);

        // Refresh token
        $schemas['NewToken'] = $schemas['Token'];
        $schemas['OldToken'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'refreshToken' => [
                    'type' => 'string',
                    'example' => 'c8d060d9..........',
                ],
            ],
        ]);

        $pathItemAuthenticationToken = new Model\PathItem( 
            ref: 'JWT Token',
            post: new Model\Operation(
                operationId: 'postCredentialsItem',
                tags: ['Authentication'],
                responses: [
                    '200' => [
                        'description' => 'Get JWT token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/Token',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Get JWT token.',
                requestBody: new Model\RequestBody(
                    description: 'Generate new JWT Token',
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/Credentials',
                            ],
                        ],
                    ]),
                ),
            ),
        );

        $pathItemRefreshToken = new Model\PathItem(
            ref: 'JWT Token',
            post: new Model\Operation(
                operationId: 'postRefreshTokenItem',
                tags: ['Authentication'],
                responses: [
                    '200' => [
                        'description' => 'Refresh JWT token',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/NewToken',
                                ],
                            ],
                        ],
                    ],
                ],
                summary: 'Refresh JWT token.',
                requestBody: new Model\RequestBody(
                    description: 'Refresh JWT Token',
                    content: new \ArrayObject([
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/OldToken',
                            ],
                        ],
                    ]),
                ),
            ),
        );

        $openApi->getPaths()->addPath('/api/authentication', $pathItemAuthenticationToken);
        $openApi->getPaths()->addPath('/api/refresh-token', $pathItemRefreshToken);

        return $openApi;
    }
}
