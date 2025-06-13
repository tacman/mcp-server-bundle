<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\IO;

class ToolResponse
{
    public function __construct(
        public readonly mixed $content,
        public bool $isError = false,
    ) {
    }
}
