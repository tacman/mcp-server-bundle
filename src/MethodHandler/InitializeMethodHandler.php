<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\MethodHandler;

use Ecourty\McpServerBundle\Attribute\AsMethodHandler;
use Ecourty\McpServerBundle\Contract\MethodHandlerInterface;
use Ecourty\McpServerBundle\Event\InitializeEvent;
use Ecourty\McpServerBundle\HttpFoundation\JsonRpcRequest;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Handles the 'initialize' method for the MCP server.
 *
 * This method is called when a client initializes a connection to the MCP server.
 * It returns the protocol version and capabilities of the server.
 */
#[AsMethodHandler(methodName: 'initialize')]
class InitializeMethodHandler implements MethodHandlerInterface
{
    private const string PROTOCOL_VERSION = '2025-03-26';

    public function __construct(
        private readonly ?EventDispatcherInterface $eventDispatcher = null,
    ) {
    }

    public function handle(JsonRpcRequest $request): array
    {
        if ($this->eventDispatcher !== null) {
            $this->eventDispatcher->dispatch(new InitializeEvent($request));
        }

        return [
            'protocolVersion' => self::PROTOCOL_VERSION,
            'capabilities' => [
                'logging' => [],
                'prompts' => [],
                'resources' => [],
                'tools' => [],
            ],
            'serverInfo' => [
                'name' => 'MCP Server',
                'version' => '1.0.0',
            ],
        ];
    }
}
