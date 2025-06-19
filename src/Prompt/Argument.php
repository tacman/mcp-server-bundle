<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Prompt;

/**
 * Represents an argument within a prompt.
 */
class Argument
{
    /**
     * @param bool $required    indicates if the argument is required (will throw an error if true and not provided)
     * @param bool $allowUnsafe indicates if the argument allows unsafe content (will not be sanitized if true)
     */
    public function __construct(
        public readonly string $name,
        public readonly string $description,
        public readonly bool $required = true,
        public readonly bool $allowUnsafe = false,
    ) {
    }

    /**
     * @return array{name: string, description: string, required: bool, allowUnsafe: bool}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'required' => $this->required,
            'allowUnsafe' => $this->allowUnsafe,
        ];
    }
}
