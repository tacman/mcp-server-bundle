<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Prompt;

/**
 * Represents a definition of an available prompt.
 */
class PromptDefinition
{
    public readonly string $name;
    public readonly ?string $description;
    public readonly ?array $arguments;

    /**
     * @param array<Argument|mixed> $arguments
     */
    public function __construct(
        string $name,
        ?string $description = null,
        array $arguments = [],
    ) {
        $this->name = $name;
        $this->description = $description;

        foreach ($arguments as $argument) {
            if ($argument instanceof Argument === false) {
                throw new \InvalidArgumentException(\sprintf(
                    'Expected instance of %s, got %s',
                    Argument::class,
                    \is_object($argument) ? \get_class($argument) : \gettype($argument),
                ));
            }
        }

        // If no arguments are provided, we set it to null.
        $this->arguments = \count($arguments) > 0
            ? $arguments
            : null;
    }
}
