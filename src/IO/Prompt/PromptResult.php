<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\IO\Prompt;

/**
 * Represents the result of a prompt, containing a description and an array of messages.
 */
class PromptResult
{
    /** @var PromptMessage[] */
    private readonly array $messages;

    public function __construct(
        private readonly string $description,
        array $messages = [],
    ) {
        foreach ($messages as $message) {
            if ($message instanceof PromptMessage === false) {
                throw new \InvalidArgumentException(\sprintf('All messages must be instances of %s.', PromptMessage::class));
            }
        }

        $this->messages = $messages;
    }

    public function toArray(): array
    {
        return [
            'description' => $this->description,
            'messages' => array_map(fn (PromptMessage $promptMessage) => $promptMessage->toArray(), $this->messages),
        ];
    }
}
