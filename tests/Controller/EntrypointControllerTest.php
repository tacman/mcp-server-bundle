<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Tests\Controller;

use Ecourty\McpServerBundle\Enum\McpErrorCode;
use Ecourty\McpServerBundle\Tests\WebTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @coversDefaultClass \Ecourty\McpServerBundle\Controller\EntrypointController
 */
class EntrypointControllerTest extends WebTestCase
{
    /**
     * @covers ::entrypointAction
     */
    public function testIndex(): void
    {
        $response = $this->request(
            method: Request::METHOD_POST,
            url: '/mcp',
        );

        $responseContent = json_decode((string) $response->getContent(), true);

        $this->assertSame([
            'jsonrpc' => '2.0',
            'id' => null,
            'result' => null,
            'error' => [
                'code' => McpErrorCode::PARSE_ERROR->value,
                'message' => McpErrorCode::PARSE_ERROR->getMessage(),
                'data' => null,
            ],
        ], $responseContent);
    }

    /**
     * @covers ::entrypointAction
     * @covers \Ecourty\McpServerBundle\MethodHandler\ToolsListMethodHandler
     */
    public function testToolList(): void
    {
        $requestId = uniqid('request_id_');

        $response = $this->request(
            method: Request::METHOD_POST,
            url: '/mcp',
            body: [
                'id' => $requestId,
                'method' => 'tools/list',
                'params' => [],
            ],
        );

        $responseContent = json_decode((string) $response->getContent(), true);
        $this->assertNotFalse($responseContent);

        $this->assertSame('2.0', $responseContent['jsonrpc']);
        $this->assertSame($requestId, $responseContent['id']);
        $this->assertArrayHasKey('result', $responseContent);

        $resultContent = $responseContent['result'];

        $this->assertArrayNotHasKey('isError', $resultContent);

        $this->assertArrayHasKey('tools', $resultContent);
        $this->assertCount(3, $resultContent['tools']);

        $tools = $resultContent['tools'];

        $this->assertSame('create_user', $tools[0]['name']);
        $this->assertSame('Creates a user based on the provided data', $tools[0]['description']);

        $this->assertSame('multiply_numbers', $tools[1]['name']);
        $this->assertSame('Calculates the product of two numbers', $tools[1]['description']);

        $this->assertSame('sum_numbers', $tools[2]['name']);
        $this->assertSame('Calculates the sum of two numbers', $tools[2]['description']);

        $this->assertArrayHasKey('inputSchema', $tools[0]);
        $this->assertSame([
            'type' => 'object',
            'properties' => [
                'emailAddress' => [
                    'description' => 'The email address of the user',
                    'type' => 'string',
                    'maxLength' => 255,
                    'minLength' => 5,
                    'nullable' => false,
                ],
                'username' => [
                    'description' => 'The username of the user',
                    'type' => 'string',
                    'maxLength' => 50,
                    'minLength' => 3,
                    'nullable' => false,
                ],
            ],
            'required' => ['emailAddress', 'username'],
        ], $tools[0]['inputSchema']);
        $this->assertArrayHasKey('annotations', $tools[0]);
        $this->assertSame([
            'title' => 'Create User',
            'readOnlyHint' => false,
            'destructiveHint' => false,
            'idempotentHint' => false,
            'openWorldHint' => false,
        ], $tools[0]['annotations']);

        $this->assertArrayHasKey('inputSchema', $tools[1]);
        $this->assertSame([
            'type' => 'object',
            'properties' => [
                'number1' => [
                    'description' => 'The first number to multiply',
                    'type' => 'number',
                    'nullable' => false,
                ],
                'number2' => [
                    'description' => 'The second number to multiply',
                    'type' => 'number',
                    'nullable' => false,
                ],
            ],
        ], $tools[1]['inputSchema']);

        $this->assertArrayHasKey('annotations', $tools[1]);
        $this->assertSame([
            'title' => 'Multiply Numbers',
            'readOnlyHint' => true,
            'destructiveHint' => false,
            'idempotentHint' => false,
            'openWorldHint' => false,
        ], $tools[1]['annotations']);

        $this->assertArrayHasKey('inputSchema', $tools[2]);
        $this->assertSame([
            'type' => 'object',
            'properties' => [
                'number1' => [
                    'description' => 'The first number to sum',
                    'type' => 'number',
                    'nullable' => false,
                ],
                'number2' => [
                    'description' => 'The second number to sum',
                    'type' => 'number',
                    'nullable' => false,
                ],
            ],
        ], $tools[2]['inputSchema']);

        $this->assertArrayHasKey('annotations', $tools[2]);
        $this->assertSame([
            'title' => '',
            'readOnlyHint' => false,
            'destructiveHint' => true,
            'idempotentHint' => false,
            'openWorldHint' => true,
        ], $tools[2]['annotations']);
    }

    /**
     * @covers ::entrypointAction
     * @covers \Ecourty\McpServerBundle\MethodHandler\ToolsCallMethodHandler
     */
    public function testToolCallWithNonExistingTool(): void
    {
        $response = $this->request(
            method: Request::METHOD_POST,
            url: '/mcp',
            body: [
                'method' => 'tools/non_existing_tool',
                'params' => [],
            ],
        );

        $responseContent = json_decode((string) $response->getContent(), true);
        $this->assertNotFalse($responseContent);

        $this->assertSame([
            'jsonrpc' => '2.0',
            'id' => null,
            'result' => null,
            'error' => [
                'code' => McpErrorCode::TOOL_NOT_FOUND->value,
                'message' => McpErrorCode::TOOL_NOT_FOUND->getMessage(),
                'data' => null,
            ],
        ], $responseContent);
    }

    /**
     * @covers ::entrypointAction
     * @covers \Ecourty\McpServerBundle\MethodHandler\ToolsCallMethodHandler
     */
    public function testToolCallWithInvalidParams(): void
    {
        $response = $this->request(
            method: Request::METHOD_POST,
            url: '/mcp',
            body: [
                'method' => 'tools/call',
                'params' => [
                    'name' => 'sum_numbers',
                    'arguments' => ['a', 1],
                ],
            ],
        );

        $responseContent = json_decode((string) $response->getContent(), true);
        $this->assertNotFalse($responseContent);

        $this->assertSame([
            'jsonrpc' => '2.0',
            'id' => null,
            'result' => null,
            'error' => [
                'code' => McpErrorCode::INTERNAL_ERROR->value,
                'message' => McpErrorCode::INTERNAL_ERROR->getMessage(),
                'data' => null,
            ],
        ], $responseContent);
    }

    /**
     * @covers ::entrypointAction
     * @covers \Ecourty\McpServerBundle\MethodHandler\ToolsCallMethodHandler
     */
    #[DataProvider('provideTestToolCalls')]
    public function testToolCalls(string $method, array $params, mixed $expectedResponse): void
    {
        $response = $this->request(
            method: Request::METHOD_POST,
            url: '/mcp',
            body: [
                'method' => $method,
                'params' => $params,
            ],
        );

        $responseContent = json_decode((string) $response->getContent(), true);
        $this->assertNotFalse($responseContent);

        $this->assertSame($expectedResponse, $responseContent);
    }

    public static function provideTestToolCalls(): \Generator
    {
        yield 'Sum two numbers' => [
            'method' => 'tools/call',
            'params' => [
                'name' => 'sum_numbers',
                'arguments' => [
                    'number1' => 5,
                    'number2' => 10,
                ],
            ],
            'expectedResponse' => [
                'jsonrpc' => '2.0',
                'id' => null,
                'result' => [
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => '15',
                        ],
                    ],
                ],
                'error' => null,
            ],
        ];

        yield 'Multiply two numbers' => [
            'method' => 'tools/call',
            'params' => [
                'name' => 'multiply_numbers',
                'arguments' => [
                    'number1' => 3,
                    'number2' => 4,
                ],
            ],
            'expectedResponse' => [
                'jsonrpc' => '2.0',
                'id' => null,
                'result' => [
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => '12',
                        ],
                    ],
                ],
                'error' => null,
            ],
        ];
    }
}
