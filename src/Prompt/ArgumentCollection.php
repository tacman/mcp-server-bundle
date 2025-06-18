<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Prompt;

/**
 * Simple collection to hold arguments for prompts.
 */
class ArgumentCollection
{
    /** @var array<string, int|string|null> */
    private array $arguments = [];

    public function add(string $name, mixed $value): self
    {
        $this->arguments[$name] = $value;

        return $this;
    }

    public function get(string $name): int|string|null
    {
        return $this->arguments[$name] ?? null;
    }

    public function has(string $name): bool
    {
        return isset($this->arguments[$name]);
    }

    public function toArray(): array
    {
        return $this->arguments;
    }

    public static function fromArray(array $arguments): self
    {
        $collection = new self();

        foreach ($arguments as $name => $value) {
            $collection->add($name, $value);
        }

        return $collection;
    }
}
