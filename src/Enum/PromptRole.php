<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Enum;

/**
 * Enum representing the roles in a prompt.
 *
 * This enum defines the different roles that can be used in a prompt,
 * such as system, user, and assistant.
 */
enum PromptRole: string
{
    case SYSTEM = 'system';
    case USER = 'user';
    case ASSISTANT = 'assistant';
}
