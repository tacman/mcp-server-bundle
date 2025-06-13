<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\MethodHandler;

use Ecourty\McpServerBundle\Attribute\AsMethodHandler;
use Ecourty\McpServerBundle\Contract\MethodHandlerInterface;
use Ecourty\McpServerBundle\HttpFoundation\JsonRpcRequest;
use Ecourty\McpServerBundle\Service\ToolRegistry;

#[AsMethodHandler(methodName: 'tools/list')]
class ToolsListMethodHandler implements MethodHandlerInterface
{
    public function __construct(
        private readonly ToolRegistry $toolRegistry,
    ) {
    }

    public function handle(JsonRpcRequest $request): array
    {
        return [
            'tools' => $this->toolRegistry->getToolsDefinitions(),
        ];
    }
}
