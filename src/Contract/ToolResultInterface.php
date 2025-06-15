<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Contract;

/**
 * Represents a result from a tool execution.
 */
interface ToolResultInterface
{
    public function toArray(): array;
}
