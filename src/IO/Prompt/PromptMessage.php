<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\IO\Prompt;

use Ecourty\McpServerBundle\Contract\Prompt\PromptMessageContentInterface;
use Ecourty\McpServerBundle\Enum\PromptRole;

/**
 * Represents a message in a prompt, containing a role and content.
 *
 * @see https://modelcontextprotocol.io/specification/2025-03-26/server/prompts#promptmessage
 */
class PromptMessage
{
    public function __construct(
        private readonly PromptRole $role,
        private readonly PromptMessageContentInterface $content,
    ) {
    }

    public function toArray(): array
    {
        return [
            'role' => $this->role->value,
            'content' => $this->content->toArray(),
        ];
    }
}
