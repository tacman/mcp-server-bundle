<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\TestApp\Tool;

use Ecourty\McpServerBundle\Attribute\AsTool;
use Ecourty\McpServerBundle\IO\TextToolResult;
use Ecourty\McpServerBundle\IO\ToolResult;
use Ecourty\McpServerBundle\TestApp\Model\SumNumbers;

#[AsTool(
    name: 'sum_numbers',
    description: 'Calculates the sum of two numbers',
)]
class SumNumbersTool
{
    public function __invoke(SumNumbers $data): ToolResult
    {
        $sum = $data->number1 + $data->number2;

        return new ToolResult(elements: [new TextToolResult(content: (string) $sum)]);
    }
}
