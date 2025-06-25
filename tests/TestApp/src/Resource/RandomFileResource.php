<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\TestApp\Resource;

use Ecourty\McpServerBundle\Attribute\AsResource;
use Ecourty\McpServerBundle\IO\Resource\BinaryResource;
use Ecourty\McpServerBundle\IO\Resource\ResourceResult;

#[AsResource(
    uri: 'file://random',
    name: 'random_file',
    title: 'Get a random file',
    description: 'This resource returns the content of a random file.',
    mimeType: 'text/plain',
)]
class RandomFileResource
{
    public function __invoke(): ResourceResult
    {
        return new ResourceResult([
            new BinaryResource(
                uri: 'file://random',
                name: 'random.txt',
                title: 'Random File',
                mimeType: 'text/plain',
                blob: bin2hex(random_bytes(32)),
            ),
        ]);
    }
}
