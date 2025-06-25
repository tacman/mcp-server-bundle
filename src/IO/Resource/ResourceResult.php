<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\IO\Resource;

use Ecourty\McpServerBundle\Contract\Resource\ResourceResultInterface;

/**
 * Represents the result of a resource operation, containing an array of resources.
 */
class ResourceResult
{
    /** @var ResourceResultInterface[] */
    private readonly array $resources;

    public function __construct(
        array $resources = [],
    ) {
        foreach ($resources as $resource) {
            if ($resource instanceof ResourceResultInterface === false) {
                throw new \InvalidArgumentException(
                    'All resources must implement ResourceResultInterface.',
                );
            }
        }

        $this->resources = $resources;
    }

    /**
     * @return array[]
     */
    public function toArray(): array
    {
        return array_map(function (ResourceResultInterface $resource) {
            return $resource->toArray();
        }, $this->resources);
    }
}
