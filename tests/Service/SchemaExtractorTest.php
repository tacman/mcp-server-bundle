<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Tests\Service;

use Ecourty\McpServerBundle\Service\SchemaExtractor;
use Ecourty\McpServerBundle\Tests\Fixture\Model\TestSchema;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \Ecourty\McpServerBundle\Service\SchemaExtractor
 */
class SchemaExtractorTest extends TestCase
{
    private SchemaExtractor $extractor;

    protected function setUp(): void
    {
        $this->extractor = new SchemaExtractor();
    }

    public function testExtractSchema(): void
    {
        $expectedSchema = [
            'type' => 'object',
            'properties' => [
                'isActive' => [
                    'description' => 'A boolean value',
                    'type' => 'boolean',
                    'nullable' => false,
                ],
                'value' => [
                    'description' => 'A number',
                    'type' => 'number',
                    'nullable' => false,
                ],
                'name' => [
                    'description' => 'A string',
                    'type' => 'string',
                    'nullable' => true,
                ],
                'lines' => [
                    'description' => 'An array of strings',
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                    ],
                    'enum' => ['enum1', 'enum2'],
                    'nullable' => false,
                ],
                'nested' => [
                    'description' => 'An array with nested properties',
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'nestedProperty1' => [
                                'description' => 'First nested property',
                                'type' => 'string',
                                'nullable' => true,
                            ],
                            'nestedProperty2' => [
                                'description' => 'Second nested property',
                                'type' => 'integer',
                                'nullable' => true,
                            ],
                        ],
                    ],
                    'nullable' => false,
                ],
                'nestedObject' => [
                    'description' => 'A nested schema object',
                    'type' => 'object',
                    'nullable' => true,
                    'properties' => [
                        'nestedProperty1' => [
                            'description' => 'First nested property',
                            'type' => 'string',
                            'nullable' => true,
                        ],
                        'nestedProperty2' => [
                            'description' => 'Second nested property',
                            'type' => 'integer',
                            'nullable' => true,
                        ],
                    ],
                ],
            ],
            'description' => 'A nested schema for testing',
            'required' => ['isActive', 'value'],
        ];

        $schema = $this->extractor->extract(TestSchema::class);

        $this->assertSame($expectedSchema, $schema, 'Extracted schema does not match expected schema');
    }
}
