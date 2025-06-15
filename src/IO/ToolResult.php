<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\IO;

use Ecourty\McpServerBundle\Contract\ToolResultInterface;

class ToolResult
{
    /** @var ToolResultInterface[] $elements */
    private array $elements = [];
    private bool $isError = false;

    public function __construct(
        array $elements = [],
        bool $isError = false,
    ) {
        foreach ($elements as $content) {
            if ($content instanceof ToolResultInterface === false) {
                throw new \InvalidArgumentException('All elements must implement ToolResultInterface');
            }
        }

        $this->elements = $elements;
        $this->isError = $isError;
    }

    public function add(ToolResultInterface $toolResult): self
    {
        $this->elements[] = $toolResult;

        return $this;
    }

    public function toArray(): array
    {
        $result = [];

        if ($this->isError === true) {
            $result['isError'] = true;
        }

        foreach ($this->elements as $element) {
            $result['content'][] = $element->toArray();
        }

        return $result;
    }
}
