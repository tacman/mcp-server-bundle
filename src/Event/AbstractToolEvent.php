<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Event;

abstract class AbstractToolEvent
{
    public function __construct(
        private readonly string $toolName,
    ) {
    }

    public function getToolName(): string
    {
        return $this->toolName;
    }
}
