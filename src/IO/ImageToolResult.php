<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\IO;

use Ecourty\McpServerBundle\Contract\ToolResultInterface;

/**
 * Represents an image result.
 *
 * @see https://modelcontextprotocol.io/specification/2025-03-26/server/tools#image-content
 */
class ImageToolResult implements ToolResultInterface
{
    public readonly string $type;

    public function __construct(
        public readonly string $data,
        public readonly string $mimeType,
    ) {
        $this->type = 'image';
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'data' => $this->data,
            'mimeType' => $this->mimeType,
        ];
    }
}
