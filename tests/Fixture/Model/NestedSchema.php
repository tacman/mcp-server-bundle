<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Tests\Fixture\Model;

use OpenApi\Attributes as OA;

class NestedSchema
{
    #[OA\Property(description: 'First nested property', type: 'string', nullable: true)]
    public ?string $nestedProperty1 = null;

    #[OA\Property(description: 'Second nested property', type: 'integer', nullable: true)]
    public ?int $nestedProperty2 = null;
}
