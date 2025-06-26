<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Event\Resource;

abstract class AbstractResourceEvent
{
    public function __construct(
        private readonly string $uri,
    ) {
    }

    public function getUri(): string
    {
        return $this->uri;
    }
}
