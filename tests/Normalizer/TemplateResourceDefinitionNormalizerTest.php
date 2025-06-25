<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Tests\Normalizer;

use Ecourty\McpServerBundle\Normalizer\TemplateResourceDefinitionNormalizer;
use Ecourty\McpServerBundle\Resource\TemplateResourceDefinition;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @coversDefaultClass \Ecourty\McpServerBundle\Normalizer\TemplateResourceDefinitionNormalizer
 */
class TemplateResourceDefinitionNormalizerTest extends KernelTestCase
{
    private TemplateResourceDefinitionNormalizer $normalizer;

    protected function setUp(): void
    {
        /** @var TemplateResourceDefinitionNormalizer $normalizer */
        $normalizer = self::getContainer()->get(TemplateResourceDefinitionNormalizer::class);

        $this->normalizer = $normalizer;
    }

    public function testNormalize(): void
    {
        $resourceDefinition = new TemplateResourceDefinition('uri', 'name', 'title', 'description', 'text/plain');
        $normalized = $this->normalizer->normalize($resourceDefinition);

        $this->assertSame([
            'name' => 'name',
            'title' => 'title',
            'description' => 'description',
            'mimeType' => 'text/plain',
            'uriTemplate' => 'uri',
        ], $normalized);
    }
}
