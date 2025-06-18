<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Tests\Controller;

use Ecourty\McpServerBundle\Enum\McpErrorCode;
use Ecourty\McpServerBundle\Enum\PromptRole;
use Ecourty\McpServerBundle\MethodHandler\InitializeMethodHandler;
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
            'error' => [
                'code' => McpErrorCode::PARSE_ERROR->value,
                'message' => McpErrorCode::PARSE_ERROR->getMessage(),
            ],
        ], $responseContent);
    }

    public function testInitialize(): void
    {
        $response = $this->request(
            method: Request::METHOD_POST,
            url: '/mcp',
            body: [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'initialize',
                'params' => [],
            ],
        );

        $responseContent = json_decode((string) $response->getContent(), true);
        $this->assertNotFalse($responseContent);

        $this->assertArrayHasKey('result', $responseContent);
        $this->assertArrayNotHasKey('error', $responseContent);

        $resultContent = $responseContent['result'];

        $this->assertArrayHasKey('protocolVersion', $resultContent);
        $this->assertSame(InitializeMethodHandler::PROTOCOL_VERSION, $resultContent['protocolVersion']);

        $this->assertArrayHasKey('serverInfo', $resultContent);
        $serverInfo = $resultContent['serverInfo'];

        $this->assertArrayHasKey('name', $serverInfo);
        $this->assertSame('My Test MCP Server', $serverInfo['name']);

        $this->assertArrayHasKey('version', $serverInfo);
        $this->assertSame('1.0.1', $serverInfo['version']);
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
                'jsonrpc' => '2.0',
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
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'tools/non_existing_tool',
                'params' => [],
            ],
        );

        $responseContent = json_decode((string) $response->getContent(), true);
        $this->assertNotFalse($responseContent);

        $this->assertSame([
            'jsonrpc' => '2.0',
            'id' => 1,
            'error' => [
                'code' => McpErrorCode::TOOL_NOT_FOUND->value,
                'message' => McpErrorCode::TOOL_NOT_FOUND->getMessage(),
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
                'jsonrpc' => '2.0',
                'id' => 1,
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
            'id' => 1,
            'error' => [
                'code' => McpErrorCode::INTERNAL_ERROR->value,
                'message' => McpErrorCode::INTERNAL_ERROR->getMessage(),
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
                'jsonrpc' => '2.0',
                'id' => 1,
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
                'id' => 1,
                'result' => [
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => '15',
                        ],
                    ],
                ],
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
                'id' => 1,
                'result' => [
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => '12',
                        ],
                    ],
                ],
            ],
        ];
    }

    public function testPromptList(): void
    {
        $response = $this->request(
            method: Request::METHOD_POST,
            url: '/mcp',
            body: [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'prompts/list',
                'params' => [],
            ],
        );

        $responseContent = json_decode((string) $response->getContent(), true);
        $this->assertNotFalse($responseContent);

        $this->assertSame('2.0', $responseContent['jsonrpc']);
        $this->assertSame(1, $responseContent['id']);
        $this->assertArrayHasKey('result', $responseContent);

        $resultContent = $responseContent['result'];

        $this->assertArrayHasKey('prompts', $resultContent);
        $this->assertCount(2, $resultContent['prompts']);

        $prompts = $resultContent['prompts'];
        $this->assertSame('generate-git-commit-message', $prompts[0]['name']);
        $this->assertSame('Generate a git commit message based on the provided changes.', $prompts[0]['description']);
        $this->assertArrayHasKey('arguments', $prompts[0]);
        $this->assertCount(2, $prompts[0]['arguments']);

        $this->assertSame('changes', $prompts[0]['arguments'][0]['name']);
        $this->assertSame('The changed made in the codebase', $prompts[0]['arguments'][0]['description']);
        $this->assertSame('scope', $prompts[0]['arguments'][1]['name']);
        $this->assertSame('The scope of the changes, e.g., feature, bugfix, etc.', $prompts[0]['arguments'][1]['description']);

        $this->assertSame('greeting', $prompts[1]['name']);
        $this->assertArrayNotHasKey('description', $prompts[1]);
        $this->assertArrayHasKey('arguments', $prompts[1]);

        $this->assertCount(1, $prompts[1]['arguments']);
        $this->assertSame('name', $prompts[1]['arguments'][0]['name']);
        $this->assertSame('The name of the person to greet.', $prompts[1]['arguments'][0]['description']);
        $this->assertFalse($prompts[1]['arguments'][0]['required']);
    }

    public function testPromptGet(): void
    {
        $changes = 'Fixed a bug in the user authentication flow';
        $scope = 'bugfix';

        $response = $this->request(
            method: Request::METHOD_POST,
            url: '/mcp',
            body: [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'prompts/get',
                'params' => [
                    'name' => 'generate-git-commit-message',
                    'arguments' => [
                        'changes' => $changes,
                        'scope' => $scope,
                    ],
                ],
            ],
        );

        $responseContent = json_decode((string) $response->getContent(), true);
        $this->assertNotFalse($responseContent);

        $this->assertSame('2.0', $responseContent['jsonrpc']);
        $this->assertSame(1, $responseContent['id']);
        $this->assertArrayHasKey('result', $responseContent);

        $resultContent = $responseContent['result'];

        // Check if the result contains the expected prompt structure
        $this->assertSame('A concise git commit message prompt', $resultContent['description']);
        $this->assertArrayHasKey('messages', $resultContent);
        $this->assertCount(1, $resultContent['messages']);

        // Check the first message in the result
        $message = $resultContent['messages'][0];
        $this->assertSame(PromptRole::USER->value, $message['role']);
        $this->assertArrayHasKey('content', $message);
        $content = $message['content'];

        // Check the content type and text
        $this->assertSame('text', $content['type']);
        $this->assertArrayHasKey('text', $content);

        // Verify that the content text contains the changes and scope
        $this->assertStringContainsString($changes, $content['text']);
        $this->assertStringContainsString($scope, $content['text']);
    }

    public function testPromptGetWithNonRequiredParameterLeftEmpty(): void
    {
        $response = $this->request(
            method: Request::METHOD_POST,
            url: '/mcp',
            body: [
                'jsonrpc' => '2.0',
                'id' => 1,
                'method' => 'prompts/get',
                'params' => [
                    'name' => 'greeting',
                ],
            ],
        );

        $responseContent = json_decode((string) $response->getContent(), true);
        $this->assertNotFalse($responseContent);

        $this->assertSame('2.0', $responseContent['jsonrpc']);
        $this->assertSame(1, $responseContent['id']);
        $this->assertArrayHasKey('result', $responseContent);
        $this->assertArrayNotHasKey('error', $responseContent);
    }
}
