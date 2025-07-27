<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\TestApp\Prompt;

use Ecourty\McpServerBundle\Attribute\AsPrompt;
use Ecourty\McpServerBundle\Enum\PromptRole;
use Ecourty\McpServerBundle\IO\Prompt\Content\TextContent;
use Ecourty\McpServerBundle\IO\Prompt\PromptMessage;
use Ecourty\McpServerBundle\IO\Prompt\PromptResult;

#[AsPrompt(
    name: 'say_hello',
    description: 'Says hello',
)]
class SayHelloPrompt
{
    public function __invoke(): PromptResult
    {
        return new PromptResult(
            description: 'Says hello',
            messages: [
                new PromptMessage(
                    role: PromptRole::SYSTEM,
                    content: new TextContent('Hello!'),
                ),
            ],
        );
    }
}
