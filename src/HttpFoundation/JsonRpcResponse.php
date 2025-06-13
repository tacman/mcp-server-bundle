<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\HttpFoundation;

class JsonRpcResponse
{
    public string $jsonrpc = '2.0';

    public function __construct(
        public int|string|null $id,
        public mixed $result = null,
        public ?JsonRpcError $error = null,
    ) {
    }
}
