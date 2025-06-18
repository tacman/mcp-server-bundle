<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Event\Prompt;

use Ecourty\McpServerBundle\IO\Prompt\PromptResult;
use Ecourty\McpServerBundle\Prompt\ArgumentCollection;

/**
 * Event triggered when a prompt has been successfully executed, contains the prompt name, arguments, and the result.
 */
class PromptResultEvent extends PromptGetEvent
{
    public function __construct(
        string $promptName,
        ArgumentCollection $arguments,
        private readonly PromptResult $result,
    ) {
        parent::__construct($promptName, $arguments);
    }

    public function getResult(): PromptResult
    {
        return $this->result;
    }
}
