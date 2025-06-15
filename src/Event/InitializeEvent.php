<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Event;

use Ecourty\McpServerBundle\HttpFoundation\JsonRpcRequest;

/**
 * This event is dispatched when the MCP server is initialized through the "initialize" JSON-RPC method.
 */
class InitializeEvent
{
    public function __construct(
        private readonly JsonRpcRequest $jsonRpcRequest,
    ) {
    }

    public function getJsonRpcRequest(): JsonRpcRequest
    {
        return $this->jsonRpcRequest;
    }
}
