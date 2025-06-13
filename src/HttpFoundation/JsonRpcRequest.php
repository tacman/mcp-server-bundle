<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\HttpFoundation;

class JsonRpcRequest
{
    public function __construct(
        public int|string|null $id,
        public string $method,
        public array $params = [],
    ) {
    }
}
