<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\IO\Prompt\Content;

/**
 * Represents a resource with a URI, data, and MIME type.
 *
 * @see https://modelcontextprotocol.io/specification/2025-03-26/server/prompts#embedded-resources
 */
class Resource
{
    public function __construct(
        public readonly string $uri,
        public readonly string $text,
        public readonly string $mimeType,
    ) {
    }

    public function toArray(): array
    {
        return [
            'uri' => $this->uri,
            'mimeType' => $this->mimeType,
            'text' => $this->text,
        ];
    }
}
