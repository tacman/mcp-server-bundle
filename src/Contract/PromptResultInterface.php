<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Contract;

/**
 * Represents a result from a prompt execution.
 */
interface PromptResultInterface
{
    public function toArray(): array;
}
