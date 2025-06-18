<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Service;

use Ecourty\McpServerBundle\DependencyInjection\CompilerPass\ToolPass;
use Ecourty\McpServerBundle\Tool\ToolDefinition;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 * Registry for tools.
 *
 * This class provides a way to retrieve tools by their name, and the definitions of all tools declared within the MCP server.
 *
 * @see ToolPass
 */
class ToolRegistry
{
    /** @var array<string, ToolDefinition> */
    private array $toolDefinitions = [];

    public function __construct(// @phpstan-ignore missingType.generics
        #[AutowireLocator(services: 'mcp_server.tool', indexAttribute: 'name')]
        private readonly ServiceLocator $toolLocator,
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
     *
     * @param array{
     *      title: string,
     *      readOnlyHint: bool,
     *      destructiveHint: bool,
     *      idempotentHint: bool,
     *      openWorldHint: bool,
     *  } $annotations
     */
    public function addToolDefinition(
        string $name,
        string $description,
        array $inputSchema,
        string $inputSchemaClass,
        array $annotations,
    ): void {
        if (isset($this->toolDefinitions[$name]) === true) {
            throw new \LogicException(\sprintf('Tool with name "%s" is already registered.', $name));
        }

        $this->toolDefinitions[$name] = new ToolDefinition(
            name: $name,
            description: $description,
            inputSchema: $inputSchema,
            inputSchemaClass: $inputSchemaClass,
            annotations: $annotations,
        );
    }
}
