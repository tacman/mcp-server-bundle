<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Prompt;

/**
 * Represents an argument within a prompt.
 */
class Argument
{
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly bool $required = true,
    ) {
    }

    /**
     * @return array{name: string, description: string, required: bool}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'required' => $this->required,
        ];
    }
}
