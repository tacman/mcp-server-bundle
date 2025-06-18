<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\IO\Prompt\Content;

use Ecourty\McpServerBundle\Contract\Prompt\PromptMessageContentInterface;

/**
 * Represents a text content in a prompt message.
 *
 * @see https://modelcontextprotocol.io/specification/2025-03-26/server/prompts#text-content
 */
class TextContent implements PromptMessageContentInterface
{
    public function __construct(
        private readonly string $text,
    ) {
    }

    public function toArray(): array
    {
        return [
            'type' => 'text',
            'text' => $this->text,
        ];
    }
}
