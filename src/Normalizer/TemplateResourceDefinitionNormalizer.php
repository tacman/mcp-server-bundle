<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Normalizer;

use Ecourty\McpServerBundle\Resource\TemplateResourceDefinition;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TemplateResourceDefinitionNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    /**
     * @param array<mixed> $context
     *
     * @return array<string, mixed>
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        if ($data instanceof TemplateResourceDefinition === false) {
            throw new \InvalidArgumentException('Expected instance of TemplateResourceDefinition.');
        }

        $normalizedData = $this->normalizer->normalize($data);
        unset($normalizedData['uri']);
        $normalizedData['uriTemplate'] = $data->uri;

        return $normalizedData;
    }

    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof TemplateResourceDefinition;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            TemplateResourceDefinition::class => true,
        ];
    }
}
