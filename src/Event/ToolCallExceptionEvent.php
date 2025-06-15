<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Event;

class ToolCallExceptionEvent extends ToolCallEvent
{
    public function __construct(
        string $toolName,
        mixed $payload,
        private readonly \Throwable $exception,
    ) {
        parent::__construct($toolName, $payload);
    }

    public function getException(): \Throwable
    {
        return $this->exception;
    }
}
