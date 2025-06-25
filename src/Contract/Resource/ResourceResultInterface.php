<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Contract\Resource;

interface ResourceResultInterface
{
    public function toArray(): array;
}
