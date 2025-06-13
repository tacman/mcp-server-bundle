<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Service;

use Ecourty\McpServerBundle\DependencyInjection\CompilerPass\ToolPass;
use Ecourty\McpServerBundle\Tool\ToolDefinition;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

class ToolRegistry
{
    /** @var array<string, ToolDefinition> */
    private array $toolDefinitions = [];

    public function __construct(
        #[AutowireLocator(services: 'mcp_server.tool', indexAttribute: 'name')]
        private readonly ContainerInterface $toolLocator,
    ) {
    }

    public function getTool(string $name): ?object
    {
        if ($this->toolLocator->has($name) === false) {
            return null;
        }

        return $this->toolLocator->get($name);
    }

    public function getToolDefinition(string $name): ?ToolDefinition
    {
        return $this->toolDefinitions[$name] ?? null;
    }

    /**
     * @return ToolDefinition[]
     */
    public function getToolsDefinitions(): array
    {
        return array_values($this->toolDefinitions);
    }

    /**
     * @internal
     *
     * @see ToolPass
     */
    public function addToolDefinition(
        string $name,
        string $description,
        array $inputSchema,
        string $inputSchemaClass,
        array $toolAnnotations,
    ): void {
        if (isset($this->toolDefinitions[$name])) {
            throw new \LogicException(\sprintf('Tool with name "%s" is already registered.', $name));
        }

        $this->toolDefinitions[$name] = new ToolDefinition(
            name: $name,
            description: $description,
            inputSchema: $inputSchema,
            inputSchemaClass: $inputSchemaClass,
            annotations: $toolAnnotations,
        );
    }
}
