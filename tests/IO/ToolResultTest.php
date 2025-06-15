<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Tests\IO;

use Ecourty\McpServerBundle\IO\AudioToolResult;
use Ecourty\McpServerBundle\IO\ImageToolResult;
use Ecourty\McpServerBundle\IO\Resource;
use Ecourty\McpServerBundle\IO\ResourceToolResult;
use Ecourty\McpServerBundle\IO\TextToolResult;
use Ecourty\McpServerBundle\IO\ToolResult;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass ToolResult
 */
class ToolResultTest extends TestCase
{
    #[DataProvider('provideToolResults')]
    public function testToolResultShouldWork(array $elements, bool $shouldPass): void
    {
        if ($shouldPass === true) {
            $this->expectNotToPerformAssertions();
        } else {
            $this->expectException(\InvalidArgumentException::class);
        }

        new ToolResult($elements);
    }

    public static function provideToolResults(): \Generator
    {
        yield 'ToolResult with empty data' => [
            'elements' => [],
            'shouldPass' => true,
        ];

        yield 'ToolResult with correct data' => [
            'elements' => [
                new TextToolResult(''),
                new ImageToolResult('', ''),
                new AudioToolResult('', ''),
                new ResourceToolResult(new Resource('', '', '')),
            ],
            'shouldPass' => true,
        ];

        yield 'ToolResult with incorrect data - array' => [
            'elements' => [
                ['invalid_content'],
            ],
            'shouldPass' => false,
        ];

        yield 'ToolResult with incorrect data - string' => [
            'elements' => [
                'invalid_content',
            ],
            'shouldPass' => false,
        ];

        yield 'ToolResult with incorrect data - null' => [
            'elements' => [
                null,
            ],
            'shouldPass' => false,
        ];

        yield 'ToolResult with incorrect data - mixed content' => [
            'elements' => [
                new TextToolResult(''),
                'invalid_content',
                new ImageToolResult('', ''),
            ],
            'shouldPass' => false,
        ];
    }
}
