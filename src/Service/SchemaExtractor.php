<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Service;

use OpenApi\Attributes\Items;
use OpenApi\Attributes\Property;
use OpenApi\Attributes\Schema;
use OpenApi\Generator;
use ReflectionClass;
use ReflectionProperty;

class SchemaExtractor
{
    /**
     * @param class-string $class
     */
    public function extract(string $class): array
    {
        $reflection = new ReflectionClass($class);
        $properties = [];

        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $attribute = $this->getPropertyAttribute($property);

            if ($attribute === null) {
                continue;
            }

            $schema = json_decode($attribute->toJson(), true);
            if (\array_key_exists('schema', $schema)) {
                unset($schema['schema']);
            }

            // Handle nested object
            if ($className = $this->resolve($attribute->schema)) {
                $schema['type'] = 'object';
                $schema['properties'] = $this->extract($className)['properties'];
            }

            // Handle array with items
            if ($schema['type'] === 'array' && $attribute->items instanceof Items) {
                $items = [];

                $itemType = $this->resolve($attribute->items->type);
                if ($itemType !== null) {
                    $items['type'] = $itemType;
                }

                $itemSchema = $this->resolve($attribute->items->schema);
                if ($itemSchema !== null) {
                    $items['type'] = 'object';
                    $items['properties'] = $this->extract($itemSchema)['properties'];
                }

                $schema['items'] = $items;
            }

            // Remove null values from schema
            $properties[$property->getName()] = array_filter($schema, static fn (mixed $v) => $v !== null);
        }

        $returnSchema = [
            'type' => 'object',
            'properties' => $properties,
        ];

        $schemaAttribute = $this->getSchemaAttribute($reflection);
        if ($schemaAttribute !== null) {
            $description = $this->resolve($schemaAttribute->description);
            if ($description !== null) {
                $returnSchema['description'] = $description;
            }

            $requiredValues = $this->resolve($schemaAttribute->required);

            if ($requiredValues !== null) {
                $returnSchema['required'] = \is_array($requiredValues) ? $requiredValues : [$requiredValues];
            }
        }

        return $returnSchema;
    }

    private function resolve(mixed $value, mixed $default = null): mixed
    {
        return $value === Generator::UNDEFINED ? $default : $value;
    }

    private function getPropertyAttribute(ReflectionProperty $property): ?Property
    {
        $propertyAttributes = $property->getAttributes(Property::class);

        if (empty($propertyAttributes)) {
            return null;
        }

        return $propertyAttributes[0]->newInstance();
    }

    private function getSchemaAttribute(ReflectionClass $class): ?Schema // @phpstan-ignore missingType.generics
    {
        $schemaAttributes = $class->getAttributes(Schema::class);

        if (empty($schemaAttributes)) {
            return null;
        }

        return $schemaAttributes[0]->newInstance();
    }
}
