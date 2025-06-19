<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Attribute;

/**
 * Tool annotations provide metadata about a tool's behavior and characteristics.
 *
 * @see https://modelcontextprotocol.io/docs/concepts/tools#available-tool-annotations
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class ToolAnnotations
{
    /**
     * @param string $title           A human-readable title for the tool, useful for UI display
     * @param bool   $readOnlyHint    If true, indicates the tool does not modify its environment
     * @param bool   $destructiveHint If true, the tool may perform destructive updates (only meaningful when readOnlyHint is false)
     * @param bool   $idempotentHint  If true, calling the tool repeatedly with the same arguments has no additional effect (only meaningful when readOnlyHint is false)
     * @param bool   $openWorldHint   If true, the tool may interact with an “open world” of external entities
     */
    public function __construct(
        public readonly string $title = '',
        public readonly bool $readOnlyHint = false,
        public readonly bool $destructiveHint = true,
        public readonly bool $idempotentHint = false,
        public readonly bool $openWorldHint = true,
    ) {
    }

    /**
     * @return array<string, string|bool>
     */
    public function asArray(): array
    {
        return [
            'title' => $this->title,
            'readOnlyHint' => $this->readOnlyHint,
            'destructiveHint' => $this->destructiveHint,
            'idempotentHint' => $this->idempotentHint,
            'openWorldHint' => $this->openWorldHint,
        ];
    }
}
