<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\MethodHandler;

use Ecourty\McpServerBundle\Attribute\AsMethodHandler;
use Ecourty\McpServerBundle\Contract\MethodHandlerInterface;
use Ecourty\McpServerBundle\HttpFoundation\JsonRpcRequest;

/**
 * Handles the 'ping' method in the MCP server.
 *
 * This method is used to check the server's availability and responsiveness.
 * It does not require any parameters and returns an empty response.
 *
 * @see https://modelcontextprotocol.io/specification/2025-03-26/basic/lifecycle
 */
#[AsMethodHandler(methodName: 'ping')]
class PingMethodHandler implements MethodHandlerInterface
{
    public function handle(JsonRpcRequest $request): array
    {
        return [];
    }
}
