<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\IO\Resource;

use Ecourty\McpServerBundle\Contract\Resource\ResourceResultInterface;

class BinaryResource implements ResourceResultInterface
{
    public function __construct(
        private readonly string $uri,
        private readonly string $name,
        private readonly string $title,
        private readonly ?string $mimeType,
        private readonly string $blob,
    ) {
    }

    public function toArray(): array
    {
        return [
            'uri' => $this->uri,
            'name' => $this->name,
            'title' => $this->title,
            'mimeType' => $this->mimeType,
            'blob' => $this->blob,
        ];
    }
}
