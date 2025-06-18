<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Contract\Prompt;

/**
 * Represents the content of a prompt message.
 */
interface PromptMessageContentInterface
{
    public function toArray(): array;
}
