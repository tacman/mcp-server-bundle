<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Attribute;

#[\Attribute(\Attribute::TARGET_CLASS)]
class AsMethodHandler
{
    /**
     * @param string $methodName The JSON-RPC method name that the handler will respond to.
     */
    public function __construct(
        public string $methodName,
    ) {
    }
}
