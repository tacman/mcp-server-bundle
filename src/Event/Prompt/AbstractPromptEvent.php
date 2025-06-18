<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Event\Prompt;

/**
 * Foundation class for prompt events.
 */
abstract class AbstractPromptEvent
{
    public function __construct(
        private readonly string $promptName,
    ) {
    }

    public function getPromptName(): string
    {
        return $this->promptName;
    }
}
