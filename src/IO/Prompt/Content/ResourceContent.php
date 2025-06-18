<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\IO\Prompt\Content;

use Ecourty\McpServerBundle\Contract\Prompt\PromptMessageContentInterface;

/**
 * Represents a resource content in a prompt message.
 *
 * @see https://modelcontextprotocol.io/specification/2025-03-26/server/prompts#embedded-resources
 */
class ResourceContent implements PromptMessageContentInterface
{
    public function __construct(
        private readonly Resource $resource,
    ) {
    }

    public function toArray(): array
    {
        return [
            'type' => 'resource',
            'resource' => $this->resource->toArray(),
        ];
    }
}
