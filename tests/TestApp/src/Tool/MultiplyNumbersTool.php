<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\TestApp\Tool;

use Ecourty\McpServerBundle\Attribute\AsTool;
use Ecourty\McpServerBundle\Attribute\ToolAnnotations;
use Ecourty\McpServerBundle\IO\TextToolResult;
use Ecourty\McpServerBundle\IO\ToolResult;
use Ecourty\McpServerBundle\TestApp\Model\MultiplyNumbers;

#[AsTool(
    name: 'multiply_numbers',
    description: 'Calculates the product of two numbers',
    annotations: new ToolAnnotations(
        title: 'Multiply Numbers',
        readOnlyHint: true,
        destructiveHint: false,
        idempotentHint: false,
        openWorldHint: false,
    ),
)]
class MultiplyNumbersTool
{
    public function __invoke(MultiplyNumbers $data): ToolResult
    {
        $sum = $data->number1 * $data->number2;

        return new ToolResult(elements: [new TextToolResult(content: (string) $sum)]);
    }
}
