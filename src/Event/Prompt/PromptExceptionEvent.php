<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Event\Prompt;

use Ecourty\McpServerBundle\Prompt\ArgumentCollection;

/**
 * Event triggered when an exception occurs during the execution of a prompt.
 *
 * Contains the prompt name, arguments if available, and the exception that was thrown.
 */
class PromptExceptionEvent extends AbstractPromptEvent
{
    public function __construct(
        string $promptName,
        private readonly ?ArgumentCollection $arguments,
        private readonly \Throwable $exception,
    ) {
        parent::__construct($promptName);
    }

    public function getArguments(): ?ArgumentCollection
    {
        return $this->arguments;
    }

    public function getException(): \Throwable
    {
        return $this->exception;
    }
}
