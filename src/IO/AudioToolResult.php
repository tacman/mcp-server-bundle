<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\IO;

use Ecourty\McpServerBundle\Contract\ToolResultInterface;

/**
 * Represents an audio result.
 *
 * @see https://modelcontextprotocol.io/specification/2025-03-26/server/tools#audio-content
 */
class AudioToolResult implements ToolResultInterface
{
    public readonly string $type;

    public function __construct(
        public readonly string $data,
        public readonly string $mimeType,
    ) {
        $this->type = 'audio';
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
