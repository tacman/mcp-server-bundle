<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\TestApp\Tool;

use Ecourty\McpServerBundle\Attribute\AsTool;
use Ecourty\McpServerBundle\Attribute\ToolAnnotations;
use Ecourty\McpServerBundle\IO\ToolResponse;
use Ecourty\McpServerBundle\TestApp\Model\CreateUserSchema;

#[AsTool(
    name: 'create_user',
    description: 'Creates a user based on the provided data',
    annotations: new ToolAnnotations(
        title: 'Create User',
        readOnlyHint: false,
        destructiveHint: false,
        idempotentHint: false,
        openWorldHint: false,
    ),
)]
class CreateUserSchemaTool
{
    public function __invoke(CreateUserSchema $data): ToolResponse
    {
        $username = $data->username;
        if ($username === 'testIsError') {
            // Simulate an error condition for testing purposes.
            return new ToolResponse(
                content: [
                    'error' => 'Simulated error for testing.',
                ],
                isError: true,
            );
        }

        // Here you would typically process the data, e.g., save it to a database or perform some business logic.
        // For demonstration purposes, we will just return a success response.

        return new ToolResponse(content: [
            'emailAddress' => $data->emailAddress,
            'username' => $username,
        ]);
    }
}
