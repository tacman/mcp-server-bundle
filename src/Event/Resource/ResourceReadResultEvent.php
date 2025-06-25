<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Event\Resource;

use Ecourty\McpServerBundle\IO\Resource\ResourceResult;

class ResourceReadResultEvent extends ResourceReadEvent
{
    public function __construct(
        string $uri,
        private readonly ResourceResult $resourceResult,
    ) {
        parent::__construct($uri);
    }

    public function getResourceResult(): ResourceResult
    {
        return $this->resourceResult;
    }
}
