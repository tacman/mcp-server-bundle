<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\TestApp\Resource;

use Ecourty\McpServerBundle\Attribute\AsResource;
use Ecourty\McpServerBundle\IO\Resource\ResourceResult;
use Ecourty\McpServerBundle\IO\Resource\TextResource;

#[AsResource(
    uri: 'database://user/{id}',
    name: 'user_data',
    title: 'Get User Data',
    description: 'Gathers the data of a user by their ID.',
    mimeType: 'application/json',
)]
class UserResource
{
    public function __invoke(int $id): ResourceResult
    {
        // Simulate fetching user data from a database
        $userData = [
            'id' => $id,
            'name' => 'User ' . $id,
            'email' => 'user' . $id . '@example.com',
        ];

        $stringUserData = (string) json_encode($userData);

        return new ResourceResult([
            new TextResource(
                uri: 'database://user/' . $id,
                name: 'user_' . $id,
                title: 'User data',
                mimeType: 'application/json',
                text: $stringUserData,
            ),
        ]);
    }
}
