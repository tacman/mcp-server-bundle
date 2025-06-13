<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Exception;

use Throwable;

class RequestHandlingException extends \Exception
{
    public function __construct(Throwable $previous)
    {
        parent::__construct($previous->getMessage(), $previous->getCode(), $previous);
    }
}
