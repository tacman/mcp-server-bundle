<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\IO\Prompt\Content;

use Ecourty\McpServerBundle\Contract\Prompt\PromptMessageContentInterface;

/**
 * Represents an image content in a prompt message.
 *
 * @see https://modelcontextprotocol.io/specification/2025-03-26/server/prompts#image-content
 */
class ImageContent implements PromptMessageContentInterface
{
    public function __construct(
        private readonly string $data,
        private readonly string $mimeType,
    ) {
    }

    public function toArray(): array
    {
        return [
            'type' => 'image',
            'data' => $this->data,
            'mimeType' => $this->mimeType,
        ];
    }
}
