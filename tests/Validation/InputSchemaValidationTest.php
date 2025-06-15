<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Tests\Validation;

use Ecourty\McpServerBundle\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @coversDefaultClass
 */
class InputSchemaValidationTest extends WebTestCase
{
    public function testInputSchemaValidation(): void
    {
        $response = $this->request(
            method: Request::METHOD_POST,
            url: '/',
            body: [
                'method' => 'tools/call',
                'params' => [
                    'name' => 'create_user',
                    'arguments' => [
                        'emailAddress' => 'incorrect-email-format',
                        'username' => 'correct-username',
                    ],
                ],
            ],
        );

        $parsedResponse = json_decode((string) $response->getContent(), true);
        $this->assertSame([
            'jsonrpc' => '2.0',
            'id' => null,
            'result' => [
                'isError' => true,
                'content' => [
                    [
                        'type' => 'text',
                        'text' => 'Validation error: emailAddress - This value is not a valid email address.',
                    ],
                ],
            ],
            'error' => null,
        ], $parsedResponse);
    }

    public function testErrorResponse(): void
    {
        $response = $this->request(
            method: Request::METHOD_POST,
            url: '/',
            body: [
                'method' => 'tools/call',
                'params' => [
                    'name' => 'create_user',
                    'arguments' => [
                        'emailAddress' => 'test@mcp.com',
                        'username' => 'testIsError', // This will trigger an error in the tool handler
                    ],
                ],
            ],
        );

        $parsedResponse = json_decode((string) $response->getContent(), true);
        $this->assertSame([
            'jsonrpc' => '2.0',
            'id' => null,
            'result' => [
                'isError' => true,
                'content' => [
                    [
                        'type' => 'text',
                        'text' => 'Simulated error for testing.',
                    ],
                ],
            ],
            'error' => null,
        ], $parsedResponse);
    }
}
