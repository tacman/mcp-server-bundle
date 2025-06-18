<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Event\Prompt;

use Ecourty\McpServerBundle\Prompt\ArgumentCollection;

/**
 * Event triggered when a prompt is requested.
 */
class PromptGetEvent extends AbstractPromptEvent
{
    public function __construct(
        string $promptName,
        private readonly ArgumentCollection $arguments,
    ) {
        parent::__construct($promptName);
    }

    public function getArguments(): ArgumentCollection
    {
        return $this->arguments;
    }
}
