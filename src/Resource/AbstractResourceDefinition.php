<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Resource;

abstract class AbstractResourceDefinition
{
    public function __construct(
        public readonly string $uri,
        public readonly string $name,
        public readonly ?string $title = null,
        public readonly ?string $description = null,
        public readonly ?string $mimeType = null,
    ) {
    }
}
