<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Tests\Service;

use Ecourty\McpServerBundle\Service\ResourceUriMatcher;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class ResourceUriMatcherTest extends TestCase
{
    private ResourceUriMatcher $matcher;

    protected function setUp(): void
    {
        $this->matcher = new ResourceUriMatcher();
    }

    #[DataProvider('provideTestDataForUriMatchTest')]
    public function testMatch(string $uriPattern, string $uri, ?array $expectedMatches): void
    {
        $matches = $this->matcher->match($uriPattern, $uri);

        $this->assertSame($expectedMatches, $matches);
    }

    public static function provideTestDataForUriMatchTest(): \Generator
    {
        yield [
            'uriPattern' => 'database://user/{id}',
            'uri' => 'database://user/123',
            'expectedMatches' => ['id' => '123'],
        ];

        yield [
            'uriPattern' => 'database://user/{id}/profile',
            'uri' => 'database://user/456/profile',
            'expectedMatches' => ['id' => '456'],
        ];

        yield [
            'uriPattern' => 'database://user/{id}/posts/{postId}',
            'uri' => 'database://user/789/posts/1011',
            'expectedMatches' => ['id' => '789', 'postId' => '1011'],
        ];

        yield [
            'uriPattern' => 'file://robots.txt',
            'uri' => 'file://robots.txt',
            'expectedMatches' => ['file://robots.txt' => 'file://robots.txt'],
        ];

        yield [
            'uriPattern' => 'file://{filename}',
            'uri' => 'file://example.txt/issou',
            'expectedMatches' => [],
        ];

        yield [
            'uriPattern' => 'file://file',
            'uri' => 'file://',
            'expectedMatches' => [],
        ];
    }
}
