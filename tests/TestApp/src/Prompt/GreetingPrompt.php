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
    name: 'greeting',
    arguments: [
        new Argument('name', description: 'The name of the person to greet.', required: false),
    ],
)]
class GreetingPrompt
{
    public function __invoke(ArgumentCollection $arguments): PromptResult
    {
        $name = $arguments->get('name');

        $prompt = $name === null
            ? 'Hello! How can I assist you today?'
            : "Hello, {$name}! How can I assist you today?";

        return new PromptResult(
            description: 'A greeting prompt without description or arguments',
            messages: [
                new PromptMessage(
                    role: PromptRole::USER,
                    content: new TextContent($prompt),
                ),
            ],
        );
    }
}
