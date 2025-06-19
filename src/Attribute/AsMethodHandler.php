<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Attribute;

/**
 * This attribute is used to mark a class as a JSON-RPC method handler.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class AsMethodHandler
{
    /**
     * @param string $methodName the JSON-RPC method name that the handler will respond to
     */
    public function __construct(
        public string $methodName,
    ) {
    }
}
