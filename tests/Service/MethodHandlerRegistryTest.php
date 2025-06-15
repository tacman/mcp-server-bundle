<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Tests\Service;

use Ecourty\McpServerBundle\MethodHandler\InitializeMethodHandler;
use Ecourty\McpServerBundle\MethodHandler\PingMethodHandler;
use Ecourty\McpServerBundle\MethodHandler\ToolsCallMethodHandler;
use Ecourty\McpServerBundle\MethodHandler\ToolsListMethodHandler;
use Ecourty\McpServerBundle\Service\MethodHandlerRegistry;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @coversDefaultClass \Ecourty\McpServerBundle\Service\MethodHandlerRegistry
 */
class MethodHandlerRegistryTest extends KernelTestCase
{
    private MethodHandlerRegistry $registry;

    protected function setUp(): void
    {
        /** @var MethodHandlerRegistry $methodHandlerRegistry */
        $methodHandlerRegistry = self::getContainer()->get(MethodHandlerRegistry::class);

        $this->registry = $methodHandlerRegistry;
    }

    /**
     * @covers ::getMethodHandler
     *
     * @param class-string $expectedMethodHandler
     */
    #[DataProvider('provideMethodAndHandlerClass')]
    public function testGetMethodHandler(string $method, string $expectedMethodHandler): void
    {
        $handler = $this->registry->getMethodHandler($method);

        $this->assertInstanceOf($expectedMethodHandler, $handler, "Handler for method '$method' should be an instance of '$expectedMethodHandler'");
    }

    public static function provideMethodAndHandlerClass(): array
    {
        return [
            ['ping', PingMethodHandler::class],
            ['initialize', InitializeMethodHandler::class],
            ['tools/list', ToolsListMethodHandler::class],
            ['tools/call', ToolsCallMethodHandler::class],
        ];
    }
}
