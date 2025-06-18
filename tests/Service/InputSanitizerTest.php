<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Tests\Service;

use Ecourty\McpServerBundle\Service\InputSanitizer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class InputSanitizerTest extends TestCase
{
    private InputSanitizer $inputSanitizer;

    protected function setUp(): void
    {
        $this->inputSanitizer = new InputSanitizer();
    }

    #[DataProvider('provideSanitizeTestData')]
    public function testSanitize(array $payload, ?array $expectedPayload): void
    {
        $sanitizedPayload = $this->inputSanitizer->sanitize($payload);

        $this->assertEquals($expectedPayload, $sanitizedPayload);
    }

    public static function provideSanitizeTestData(): \Generator
    {
        yield 'empty array' => [
            'payload' => [],
            'expectedPayload' => [],
        ];

        yield 'simple string' => [
            'payload' => ['key' => 'value'],
            'expectedPayload' => ['key' => 'value'],
        ];

        yield 'string with HTML tags' => [
            'payload' => ['key' => '<b>bold</b> text'],
            'expectedPayload' => ['key' => 'bold text'],
        ];

        yield 'string with special characters' => [
            'payload' => ['key' => '"quotes" & \'single quotes\''],
            'expectedPayload' => ['key' => '"quotes" &amp; \'single quotes\''],
        ];

        yield 'nested array with special characters' => [
            'payload' => ['key1' => ['key2' => '<script>alert("XSS")</script>']],
            'expectedPayload' => ['key1' => ['key2' => 'alert("XSS")']],
        ];

        yield 'deeply nested array with max depth exceeded' => [
            'payload' => self::generateNestedArray(600),
            'expectedPayload' => null,
        ];

        yield 'array with mixed types' => [
            'payload' => [
                'string' => 'text',
                'int' => 123,
                'float' => 12.34,
                'bool' => true,
                'null' => null,
                'nested' => ['key' => '<b>bold</b> text'],
            ],
            'expectedPayload' => [
                'string' => 'text',
                'int' => 123,
                'float' => 12.34,
                'bool' => true,
                'null' => null,
                'nested' => ['key' => 'bold text'],
            ],
        ];

        yield 'boolean value' => [
            'payload' => ['key' => true],
            'expectedPayload' => ['key' => true],
        ];

        yield 'integer value' => [
            'payload' => ['key' => 42],
            'expectedPayload' => ['key' => 42],
        ];

        yield 'float value' => [
            'payload' => ['key' => 3.14],
            'expectedPayload' => ['key' => 3.14],
        ];

        yield 'null value' => [
            'payload' => ['key' => null],
            'expectedPayload' => ['key' => null],
        ];

        yield 'complex nested structure' => [
            'payload' => [
                'level1' => [
                    'level2' => [
                        'level3' => '<script>alert("XSS")</script>',
                        'array' => ['<b>bold</b>', 123, true],
                    ],
                    'anotherLevel2' => (object) [
                        'property' => '<i>italic</i> text',
                    ],
                ],
            ],
            'expectedPayload' => [
                'level1' => [
                    'level2' => [
                        'level3' => 'alert("XSS")',
                        'array' => ['bold', 123, true],
                    ],
                    'anotherLevel2' => (object) [
                        'property' => 'italic text',
                    ],
                ],
            ],
        ];
    }

    private static function generateNestedArray(int $depth): array
    {
        if ($depth <= 0) {
            return ['key' => 'value'];
        }

        return [
            'nested' => self::generateNestedArray($depth - 1),
        ];
    }
}
