<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Tests\Fixture\Model;

use OpenApi\Attributes as OA;

#[OA\Schema(description: 'A nested schema for testing', required: ['isActive', 'value'])]
class TestSchema
{
    #[OA\Property(description: 'A boolean value', type: 'boolean', nullable: false)]
    public bool $isActive = true;

    #[OA\Property(description: 'A number', type: 'number', nullable: false)]
    public float $value = 0.0;

    #[OA\Property(description: 'A string', type: 'string', nullable: true)]
    public ?string $name = null;

    #[OA\Property(description: 'An array of strings', type: 'array', items: new OA\Items(type: 'string'), enum: ['enum1', 'enum2'], nullable: false)]
    public array $lines = [];

    #[OA\Property(description: 'An array with nested properties', type: 'array', items: new OA\Items(schema: NestedSchema::class), nullable: false)]
    public array $nested;

    #[OA\Property(schema: NestedSchema::class, description: 'A nested schema object', type: 'object', nullable: true)]
    public ?NestedSchema $nestedObject = null;
}
