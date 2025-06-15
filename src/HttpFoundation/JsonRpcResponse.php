<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\HttpFoundation;

/**
 * Represents a JSON-RPC error.
 *
 * @see https://www.jsonrpc.org/specification#response_object
 */
class JsonRpcResponse
{
    public const string JSON_RPC_VERSION = '2.0';

    public readonly string $jsonrpc;

    public function __construct(
        public int|string|null $id,
        public mixed $result = null,
        public ?JsonRpcError $error = null,
    ) {
        $this->jsonrpc = self::JSON_RPC_VERSION;
    }
}
