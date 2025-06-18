<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Tool;

use Ecourty\McpServerBundle\DependencyInjection\CompilerPass\ToolPass;
use Symfony\Component\Serializer\Attribute\Ignore;

/**
 * Represents a tool definition.
 *
 * @see ToolPass
 *
 * @internal
 */
class ToolDefinition
{
    /**
     * @param array{
     *     title: string,
     *     readOnlyHint: bool,
     *     destructiveHint: bool,
     *     idempotentHint: bool,
     *     openWorldHint: bool,
     * } $annotations
     */
    public function __construct(
        public string $name,
        public string $description,
        public array $inputSchema,
        #[Ignore]
        public string $inputSchemaClass,
        public array $annotations,
    ) {
    }
}
