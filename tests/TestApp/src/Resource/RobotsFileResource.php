<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\TestApp\Resource;

use Ecourty\McpServerBundle\Attribute\AsResource;
use Ecourty\McpServerBundle\IO\Resource\ResourceResult;
use Ecourty\McpServerBundle\IO\Resource\TextResource;

#[AsResource(
    uri: 'file://robots.txt',
    name: 'robots_txt',
    title: 'Get the Robots.txt file',
    description: 'This resource returns the content of the robots.txt file.',
    mimeType: 'text/plain',
)]
class RobotsFileResource
{
    private const string FILE_PATH = __DIR__ . '/../Resources/robots.txt';

    public function __invoke(): ResourceResult
    {
        $fileContent = (string) file_get_contents(self::FILE_PATH);

        return new ResourceResult([
            new TextResource(
                uri: 'file://robots.txt',
                name: 'robots.txt',
                title: 'The robots.txt file',
                mimeType: 'text/plain',
                text: $fileContent,
            ),
        ]);
    }
}
