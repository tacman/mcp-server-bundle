<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Tests\MethodHandler;

use Ecourty\McpServerBundle\HttpFoundation\JsonRpcRequest;
use Ecourty\McpServerBundle\MethodHandler\PingMethodHandler;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Ecourty\McpServerBundle\MethodHandler\PingMethodHandler
 */
class PingMethodHandlerTest extends TestCase
{
    private PingMethodHandler $handler;

    protected function setUp(): void
    {
        $this->handler = new PingMethodHandler();
    }

    /**
     * @covers ::handle
     */
    public function testHandle(): void
    {
        $result = $this->handler->handle(new JsonRpcRequest(1, 'ping'));

        $this->assertEmpty($result);
    }
}
