<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\HttpFoundation;

class JsonRpcError
{
    public function __construct(
        public int $code,
        public string $message,
        public ?array $data = null,
    ) {
    }
}
