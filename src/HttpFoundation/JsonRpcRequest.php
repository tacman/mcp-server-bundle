<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\HttpFoundation;

/**
 * Represents a JSON-RPC request.
 *
 * @see https://www.jsonrpc.org/specification#request_object
 */
class JsonRpcRequest
{
    public function __construct(
        public int|string|null $id,
        public string $method,
        public array $params = [],
    ) {
    }
}
