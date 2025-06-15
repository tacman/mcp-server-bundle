<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Attribute;

/**
 * This attribute is used to mark a class as a tool for the MCP Server.
 *
 * Tools are used to perform specific actions or provide functionalities that can be invoked by clients.
 * The tool's name and description are used to identify and describe the tool's purpose.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class AsTool
{
    private readonly ToolAnnotations $toolAnnotations;

    /**
     * @param string $name The name of the tool, which can be called by clients.
     * @param string $description A human-readable description of the tool, useful for LLMs to understand its purpose.
     * @param ToolAnnotations|null $annotations Optional annotations for the tool, providing additional metadata such as title, read-only status, and hints about the tool's behavior.
     */
    public function __construct(
        public string $name,
        public string $description,
        ?ToolAnnotations $annotations = null,
    ) {
        $this->toolAnnotations = $annotations ?? new ToolAnnotations();
    }

    public function getToolAnnotations(): ToolAnnotations
    {
        return $this->toolAnnotations;
    }
}
