<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Event;

/**
 * ToolCallEvent is dispatched when a tool is called using the "tools/call" JSON-RPC method.
 */
class ToolCallEvent extends AbstractToolEvent
{
    private mixed $payload;

    public function __construct(string $toolName, mixed $payload)
    {
        parent::__construct($toolName);

        $this->payload = $payload;
    }

    public function getPayload(): mixed
    {
        return $this->payload;
    }
}
