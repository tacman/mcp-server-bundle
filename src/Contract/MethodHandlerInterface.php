<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Contract;

use Ecourty\McpServerBundle\HttpFoundation\JsonRpcRequest;

/**
 * Used as service tag for method handlers.
 */
interface MethodHandlerInterface
{
    public const string KEY_NAME = 'method_name';

    public function handle(JsonRpcRequest $request): array;
}
