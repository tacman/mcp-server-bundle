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
}
