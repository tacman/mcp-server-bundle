<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Resource;

/**
 * Represents a direct resource definition (fixed path).
 *
 * @internal
 */
class DirectResourceDefinition extends AbstractResourceDefinition
{
    public function __construct(
        string $uri,
        string $name,
        ?string $title = null,
        ?string $description = null,
        ?string $mimeType = null,
        public readonly ?string $size = null,
    ) {
        parent::__construct($uri, $name, $title, $description, $mimeType);
    }
}
