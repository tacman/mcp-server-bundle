<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Tests\Service;

use Ecourty\McpServerBundle\Service\ToolRegistry;
use Ecourty\McpServerBundle\TestApp\Tool\CreateUserTool;
use Ecourty\McpServerBundle\TestApp\Tool\MultiplyNumbersTool;
use Ecourty\McpServerBundle\TestApp\Tool\SumNumbersTool;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @coversDefaultClass \Ecourty\McpServerBundle\Service\ToolRegistry
 */
class ToolRegistryTest extends KernelTestCase
{
    private ToolRegistry $registry;

    protected function setUp(): void
    {
        /** @var ToolRegistry $toolRegistry */
        $toolRegistry = self::getContainer()->get(ToolRegistry::class);

        $this->registry = $toolRegistry;
    }

    /**
     * @covers ::getTool
     *
     * @param class-string $expectedToolHandlerClass
     */
    #[DataProvider('provideToolAndHandlerClass')]
    public function testGetTool(string $toolName, string $expectedToolHandlerClass): void
    {
        $tool = $this->registry->getTool($toolName);

        $this->assertInstanceOf($expectedToolHandlerClass, $tool, "Tool '$toolName' should be an instance of '$expectedToolHandlerClass'");
    }

    public static function provideToolAndHandlerClass(): array
    {
        return [
            ['sum_numbers', SumNumbersTool::class],
            ['multiply_numbers', MultiplyNumbersTool::class],
            ['create_user', CreateUserTool::class],
        ];
    }

    #[DataProvider('provideToolAndDefinition')]
    public function testGetToolDefinition(string $toolName, string $expectedDescription, array $expectedSchema): void
    {
        $toolDefinition = $this->registry->getToolDefinition($toolName);

        $this->assertNotNull($toolDefinition);
        $this->assertSame($expectedDescription, $toolDefinition->description);
        $this->assertSame($expectedSchema, $toolDefinition->inputSchema);
    }

    public static function provideToolAndDefinition(): array
    {
        return [
            [
                'toolName' => 'sum_numbers',
                'expectedDescription' => 'Calculates the sum of two numbers',
                'expectedSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'number1' => ['description' => 'The first number to sum', 'type' => 'number', 'nullable' => false],
                        'number2' => ['description' => 'The second number to sum', 'type' => 'number', 'nullable' => false],
                    ],
                ],
            ],
            [
                'toolName' => 'multiply_numbers',
                'expectedDescription' => 'Calculates the product of two numbers',
                'expectedSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'number1' => ['description' => 'The first number to multiply', 'type' => 'number', 'nullable' => false],
                        'number2' => ['description' => 'The second number to multiply', 'type' => 'number', 'nullable' => false],
                    ],
                ],
            ],
            [
                'toolName' => 'create_user',
                'expectedDescription' => 'Creates a user based on the provided data',
                'expectedSchema' => [
                    'type' => 'object',
                    'properties' => [
                        'emailAddress' => ['description' => 'The email address of the user', 'type' => 'string', 'maxLength' => 255, 'minLength' => 5, 'nullable' => false],
                        'username' => ['description' => 'The username of the user', 'type' => 'string', 'maxLength' => 50, 'minLength' => 3, 'nullable' => false],
                    ],
                    'required' => ['emailAddress', 'username'],
                ],
            ],
        ];
    }
}
