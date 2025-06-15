<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Event;

use Ecourty\McpServerBundle\IO\ToolResult;

/**
 * This event is dispatched when a tool call has been completed and the result is available.
 */
class ToolResultEvent extends ToolCallEvent
{
    public function __construct(
        string $toolName,
        mixed $payload,
        private readonly ToolResult $result,
    ) {
        parent::__construct($toolName, $payload);
    }

    public function getResult(): ToolResult
    {
        return $this->result;
    }
}
