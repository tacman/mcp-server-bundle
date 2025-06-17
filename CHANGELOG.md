# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.1] - 2025-06-17

### Added
- Added `ResponseFactory` which handles serializing responsed into JSON-RPC-compliant responses [Link to the issue](https://github.com/EdouardCourty/mcp-server-bundle/issues/2)

### Updated
- Updated `EntrypointController` to use the new `ResponseFormatter` class to format the JSON-RPC response
- Updated `ExceptionListener` to use `ResponseFormatter` to format JSON-RPC error responses

### Removed

- Removed `composer.lock` from the repository [Link to the issue](https://github.com/EdouardCourty/mcp-server-bundle/issues/3)

## [1.0.0] - 2025-06-13

### Added
- Initial stable release of the MCP Server Bundle
- Support for Model Context Protocol (MCP) tools implementation
- JSON-RPC method handlers for tool management
- Built-in `tools/list` and `tools/call` method handlers
- Tool registration system using PHP 8 attributes
- Input schema validation and sanitization
- OpenAPI integration for tool documentation
- README documentation
