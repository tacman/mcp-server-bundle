<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Exception;

/**
 * Exception thrown when there is an error handling a JSON-RPC request.
 */
class RequestHandlingException extends \Exception
{
    public function __construct(\Throwable $previous)
    {
        parent::__construct($previous->getMessage(), $previous->getCode(), $previous);
    }
}
