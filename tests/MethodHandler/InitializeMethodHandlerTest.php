<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Tests\MethodHandler;

use Ecourty\McpServerBundle\Event\InitializeEvent;
use Ecourty\McpServerBundle\HttpFoundation\JsonRpcRequest;
use Ecourty\McpServerBundle\MethodHandler\InitializeMethodHandler;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * @coversDefaultClass \Ecourty\McpServerBundle\MethodHandler\InitializeMethodHandler
 */
class InitializeMethodHandlerTest extends TestCase
{
    private MockObject&EventDispatcherInterface $eventDispatcher;

    private InitializeMethodHandler $initializeMethodHandler;

    protected function setUp(): void
    {
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $this->initializeMethodHandler = new InitializeMethodHandler(eventDispatcher: $this->eventDispatcher);
    }

    /**
     * @covers ::handle
     */
    public function testHandleFiresEvent(): void
    {
        $request = new JsonRpcRequest(id: 1, method: 'initialize', params: []);

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with($this->isInstanceOf(InitializeEvent::class));

        $this->initializeMethodHandler->handle($request);
    }
}
