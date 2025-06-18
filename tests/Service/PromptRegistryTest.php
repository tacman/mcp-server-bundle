<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Tests\Service;

use Ecourty\McpServerBundle\Prompt\Argument;
use Ecourty\McpServerBundle\Service\PromptRegistry;
use Ecourty\McpServerBundle\TestApp\Prompt\GenerateGitCommitMessage;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PromptRegistryTest extends KernelTestCase
{
    private PromptRegistry $registry;

    protected function setUp(): void
    {
        /** @var PromptRegistry $promptRegistry */
        $promptRegistry = self::getContainer()->get(PromptRegistry::class);

        $this->registry = $promptRegistry;
    }

    /**
     * @param class-string $expectedClass
     */
    #[DataProvider('providePromptTestData')]
    public function testGetPrompt(string $name, string $expectedClass): void
    {
        $prompt = $this->registry->getPrompt($name);

        $this->assertInstanceOf($expectedClass, $prompt);
    }

    public static function providePromptTestData(): array
    {
        return [
            [
                'name' => 'generate-git-commit-message',
                'expectedClass' => GenerateGitCommitMessage::class,
            ],
        ];
    }

    #[DataProvider('providePromptDefinitionTestData')]
    public function testGetPromptDefinition(string $name, string $expectedDescription, array $expectedArguments): void
    {
        $promptDefinition = $this->registry->getPromptDefinition($name);

        $this->assertNotNull($promptDefinition);

        $this->assertSame($name, $promptDefinition->name);
        $this->assertSame($expectedDescription, $promptDefinition->description);
        $this->assertEquals($expectedArguments, $promptDefinition->arguments);
    }

    public static function providePromptDefinitionTestData(): array
    {
        return [
            [
                'name' => 'generate-git-commit-message',
                'expectedDescription' => 'Generate a git commit message based on the provided changes.',
                'expectedArguments' => [
                    new Argument('changes', 'The changed made in the codebase', true, true),
                    new Argument('scope', 'The scope of the changes, e.g., feature, bugfix, etc.', true),
                ],
            ],
        ];
    }
}
