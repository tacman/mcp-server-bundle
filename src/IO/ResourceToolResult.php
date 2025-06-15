<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\IO;

use Ecourty\McpServerBundle\Contract\ToolResultInterface;

/**
 * Represents a resource tool response with a URI, data, and MIME type.
 *
 * @see https://modelcontextprotocol.io/specification/2025-03-26/server/tools#embedded-resources
 */
class ResourceToolResult implements ToolResultInterface
{
    public readonly string $type;

    public function __construct(
        public readonly Resource $resource,
    ) {
        $this->type = 'resource';
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'resource' => $this->resource->toArray(),
        ];
    }
}
