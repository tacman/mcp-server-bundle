<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\TestApp\Tool;

use Ecourty\McpServerBundle\Attribute\AsTool;
use Ecourty\McpServerBundle\Attribute\ToolAnnotations;
use Ecourty\McpServerBundle\IO\Resource;
use Ecourty\McpServerBundle\IO\ResourceToolResult;
use Ecourty\McpServerBundle\IO\TextToolResult;
use Ecourty\McpServerBundle\IO\ToolResult;
use Ecourty\McpServerBundle\TestApp\Model\CreateUser;

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
class CreateUserTool
{
    public function __invoke(CreateUser $data): ToolResult
    {
        $username = $data->username;
        if ($username === 'testIsError') {
            // Simulate an error condition for testing purposes.
            return new ToolResult(
                elements: [new TextToolResult(
                    content: 'Simulated error for testing.',
                )],
                isError: true,
            );
        }

        // Here you would typically process the data, e.g., save it to a database or perform some business logic.
        // For demonstration purposes, we will just return a success response.

        return new ToolResult(
            elements: [
                new TextToolResult(
                    content: "User '{$username}' created successfully.",
                ),
                new ResourceToolResult(
                    resource: new Resource(
                        uri: 'entity://user/' . $username,
                        text: "Details for user '{$username}'.",
                        mimeType: 'application/json',
                    ),
                ),
            ],
        );
    }
}
