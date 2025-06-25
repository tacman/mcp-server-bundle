<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\TestApp\Resource;

use Ecourty\McpServerBundle\Attribute\AsResource;
use Ecourty\McpServerBundle\IO\Resource\ResourceResult;
use Ecourty\McpServerBundle\IO\Resource\TextResource;

#[AsResource(
    uri: 'database://order/{id}',
    name: 'order_data',
    title: 'Get Order Data',
    description: 'Gathers the data of an order by their ID.',
    mimeType: 'application/json',
)]
class OrderResource
{
    public function __invoke(int $id): ResourceResult
    {
        // Simulate fetching user data from a database
        $userData = [
            'id' => $id,
            'reference' => mb_strtoupper(md5((string) $id)),
            'status' => 'pending',
        ];

        $stringUserData = (string) json_encode($userData);

        return new ResourceResult([
            new TextResource(
                uri: 'database://order/' . $id,
                name: 'order_' . $id,
                title: 'Order data',
                mimeType: 'application/json',
                text: $stringUserData,
            ),
        ]);
    }
}
