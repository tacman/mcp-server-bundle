<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\TestApp\Tool;

use Ecourty\McpServerBundle\Attribute\AsTool;
use Ecourty\McpServerBundle\IO\TextToolResult;
use Ecourty\McpServerBundle\IO\ToolResult;

#[AsTool(
    name: 'date_time',
    description: 'Retrieve the date and time of the server',
)]
class DateTimeTool
{
    public function __invoke(): ToolResult
    {
        return new ToolResult([
            new TextToolResult(content: (new \DateTime('now'))->format('Y-m-d H:i:s')),
        ]);
    }
}
