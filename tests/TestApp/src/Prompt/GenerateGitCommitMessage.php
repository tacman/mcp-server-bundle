<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\TestApp\Prompt;

use Ecourty\McpServerBundle\Attribute\AsPrompt;
use Ecourty\McpServerBundle\Enum\PromptRole;
use Ecourty\McpServerBundle\IO\Prompt\Content\TextContent;
use Ecourty\McpServerBundle\IO\Prompt\PromptMessage;
use Ecourty\McpServerBundle\IO\Prompt\PromptResult;
use Ecourty\McpServerBundle\Prompt\ArgumentCollection;
use Ecourty\McpServerBundle\Prompt\Argument;

#[AsPrompt(
    name: 'generate-git-commit-message',
    description: 'Generate a git commit message based on the provided changes.',
    arguments: [
        new Argument(name: 'changes', description: 'The changed made in the codebase'),
        new Argument(name: 'scope', description: 'The scope of the changes, e.g., feature, bugfix, etc.'),
    ],
)]
class GenerateGitCommitMessage
{
    public function __invoke(ArgumentCollection $arguments): PromptResult
    {
        $changed = $arguments->get('changes');
        $scope = $arguments->get('scope');

        if ($scope === 'error') {
            throw new \LogicException('Simulated error for testing purposes');
        }

        $prompt = <<<PROMPT
Generate a concise and descriptive git commit message based on the following changes: {$changed}.
Here is the scope of the changes: {$scope}.
PROMPT;

        return new PromptResult(
            description: 'A concise git commit message prompt',
            messages: [
                new PromptMessage(
                    role: PromptRole::USER,
                    content: new TextContent($prompt),
                ),
            ],
        );
    }
}
