<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Service;

use Ecourty\McpServerBundle\DependencyInjection\CompilerPass\ResourcePass;
use Ecourty\McpServerBundle\Resource\AbstractResourceDefinition;
use Ecourty\McpServerBundle\Resource\DirectResourceDefinition;
use Ecourty\McpServerBundle\Resource\TemplateResourceDefinition;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 * Registry for resources.
 *
 * This class provides a way to retrieve resources by their uri, and the definitions of all resources declared within the MCP server.
 *
 * @see ResourcePass
 */
class ResourceRegistry
{
    /** @var array<string, AbstractResourceDefinition> */
    private array $resourceDefinitions = [];

    public function __construct(// @phpstan-ignore missingType.generics
        #[AutowireLocator(services: 'mcp_server.resource', indexAttribute: 'uri')]
        private readonly ServiceLocator $resourceLocator,
    ) {
    }

    public function getResource(string $uri): ?object
    {
        if ($this->resourceLocator->has($uri) === false) {
            return null;
        }

        return $this->resourceLocator->get($uri);
    }

    public function getResourceDefinition(string $name): ?AbstractResourceDefinition
    {
        return $this->resourceDefinitions[$name] ?? null;
    }

    /**
     * @return AbstractResourceDefinition[]
     */
    public function getResourceDefinitions(): array
    {
        return array_values($this->resourceDefinitions);
    }

    /**
     * @internal
     */
    public function addDirectResourceDefinition(
        string $name,
        string $uri,
        ?string $title = null,
        ?string $description = null,
        ?string $mimeType = null,
        ?string $size = null,
    ): void {
        $resourceDefinition = new DirectResourceDefinition(
            uri: $uri,
            name: $name,
            title: $title,
            description: $description,
            mimeType: $mimeType,
            size: $size,
        );

        $this->resourceDefinitions[$uri] = $resourceDefinition;
    }

    /**
     * @internal
     */
    public function addTemplateResourceDefinition(
        string $name,
        string $uri,
        ?string $title = null,
        ?string $description = null,
        ?string $mimeType = null,
    ): void {
        $resourceDefinition = new TemplateResourceDefinition(
            uri: $uri,
            name: $name,
            title: $title,
            description: $description,
            mimeType: $mimeType,
        );

        $this->resourceDefinitions[$uri] = $resourceDefinition;
    }
}
