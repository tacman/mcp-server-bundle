<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\HttpFoundation;

use Ecourty\McpServerBundle\Enum\McpErrorCode;

/**
 * Represents a JSON-RPC error.
 *
 * @see https://www.jsonrpc.org/specification#error_object
 */
class JsonRpcError
{
    public function __construct(
        public McpErrorCode $code,
        public string $message,
        public ?array $data = null,
    ) {
    }
}
