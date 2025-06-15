<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\IO;

use Ecourty\McpServerBundle\Contract\ToolResultInterface;

/**
 * Represents a text result.
 *
 * @see https://modelcontextprotocol.io/specification/2025-03-26/server/tools#text-content
 */
class TextToolResult implements ToolResultInterface
{
    public readonly string $type;

    public function __construct(
        public readonly string $content,
    ) {
        $this->type = 'text';
    }

    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'text' => $this->content,
        ];
    }
}
