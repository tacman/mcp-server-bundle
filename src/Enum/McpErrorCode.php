<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Enum;

/**
 * Enum representing error codes for the MCP server.
 *
 * This enum defines error codes used in the MCP server, including standard JSON-RPC 2.0 error codes
 * and custom error codes specific to the MCP server.
 */
enum McpErrorCode: int
{
    // Error codes for JSON-RPC 2.0
    case PARSE_ERROR = -32700;
    case INVALID_REQUEST = -32600;
    case METHOD_NOT_FOUND = -32601;
    case INVALID_PARAMS = -32602;
    case INTERNAL_ERROR = -32603;

    // Custom error codes
    case TOOL_NOT_FOUND = -31000;

    public function getMessage(): string
    {
        return match ($this) {
            self::PARSE_ERROR => 'Parse error',
            self::INVALID_REQUEST => 'Invalid request',
            self::METHOD_NOT_FOUND => 'Method not found',
            self::INVALID_PARAMS => 'Invalid params',
            self::INTERNAL_ERROR => 'Internal error',
            self::TOOL_NOT_FOUND => 'Tool not found',
        };
    }
}
