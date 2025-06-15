# MCP Server Bundle

[![PHP CI](https://github.com/EdouardCourty/mcp-server-bundle/actions/workflows/php_ci.yml/badge.svg)](https://github.com/EdouardCourty/mcp-server-bundle/actions/workflows/php_ci.yml)

A powerful Symfony bundle for handling MCP (Message Control Protocol) server implementations, providing tools for JSON-RPC request handling and tool management.    
_Read the [official MCP specification](https://modelcontextprotocol.io/docs/concepts/tools#overview)._

> [!WARNING]  
> The specification of the Model Context Protocol (MCP) changes frequently.
> This bundle will evolve along with the specification, so please ensure you are using the latest version of the bundle.  
> The CHANGELOG can be found [here](CHANGELOG.md).

## Table of Contents

- [Getting Started](#getting-started)
  - [Configuration](#configuration)
  - [Tool Handlers](#tool-handlers)
  - [Input Schema Management](#input-schema-management)
  - [JSON-RPC Method Handlers](#json-rpc-method-handlers)
  - [Developer Experience](#developer-experience)
- [Contributing](#contributing)
- [License](#license)

## Getting Started

The MCP Server Bundle provides a structured way to create and manage tools that can be used by clients via JSON-RPC requests.  
It includes features for MCP tool management, and JSON-RPC method handling.

This bundle is designed to be flexible and extensible, allowing developers to create custom tool handlers and method handlers as needed.  
MethodHandlers and ToolHandlers are registered and autowired using attributes, making it easy to define and manage your own tools.

### Installation

1. Install the MCP Server Bundle via Composer:
```bash
composer require ecourty/mcp-server-bundle
```

2. Add the bundle to your `config/bundles.php` (if not using Symfony Flex):
```php
return [
    // ...
    Ecourty\McpServerBundle\McpServerBundle::class => ['all' => true],
];
```

3. Configure the routes in `config/routes/mcp.yaml`:
```yaml
mcp_controller:
  resource: '@McpServerBundle/Controller'
```

### Tools

Tools are the core components of the MCP Server Bundle. They allow you to define and manage custom logic that can be triggered by clients.

#### Creating a Tool

1. Create a new class that will handle your tool logic
2. Use the `#[AsTool]` attribute to register your tool
3. Define the input schema for your tool using a class with validation constraints and OpenAPI attributes (examples below)
4. Implement the `__invoke` method to handle the tool logic and return a `ToolResponse`

_As Tool classes are services within the Symfony application, any dependency can be injected in it, using the constructor, like any other service._

Example:

- Tool input schema class:
```php
#[OA\Schema(required: ['emailAddress', 'username'])]
class CreateUserSchema
{
    #[Assert\Email]
    #[Assert\NotBlank]
    #[Assert\Length(min: 5, max: 255)]
    #[OA\Property(description: 'The email address of the user', type: 'string', maxLength: 255, minLength: 5, nullable: false)]
    public string $emailAddress;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 50)]
    #[OA\Property(description: 'The username of the user', type: 'string', maxLength: 50, minLength: 3, nullable: false)]
    public string $username;
}
```

- Tool class

```php
<?php

use App\Schema\CreateUserSchema; // Your input schema class
use Ecourty\McpServerBundle\Attribute\AsTool;
use Ecourty\McpServerBundle\Attribute\ToolAnnotations;
use Ecourty\McpServerBundle\IO\GenericToolResponse;

#[AsTool(
    name: 'create_user', # Unique identifier for the tool, used by clients to call it
    description: 'Creates a new user in the system', # This description is used by LLMs to understand the tool's purpose
    annotations: new ToolAnnotations(
        title: 'Create a user', // A human-readable title for the tool, useful for documentation
        readOnlyHint: false, // Defines the request is not read-only (creates a user)
        destructiveHint: false, // Defines the request is not destructive (does not delete data)
        idempotentHint: false, // Defines the request cannot be repeated without changing the state
        openWorldHint: false, // The tool does not interact with external systems
    )
)]
class CreateUserTool
{
    public function __invoke(CreateUserSchema $createUserSchema): GenericToolResponse
    {
        // Your logic here...
        // $user = new User();

        return new GenericToolResponse(data: $user);
    }
}
```

Tool Attributes

The `#[AsTool]` attribute supports the following properties:

- `name` (string, required): The unique identifier for your tool, which can be called by clients
- `description` (string, optional): A human-readable description of the tool, useful for LLMs to understand its purpose
- `annotations` (ToolAnnotations, optional): Additional metadata about the tool's behavior

The `ToolAnnotations` class provides the following properties:
- `title` (string): A human-readable title for the tool
- `readOnlyHint` (bool): Indicates if the tool only reads data
- `destructiveHint` (bool): Indicates if the tool performs destructive operations
- `idempotentHint` (bool): Indicates if the tool can be safely repeated
- `openWorldHint` (bool): Indicates if the tool interacts with external systems

### Input Schema Management

The bundle provides robust input validation and sanitization through schema-based deserialization.  
Input schemas are extracted from the `__invoke` method of classes with the `#[AsTool]` attribute, allowing you to define the expected input structure and validation rules.

1. Define your input schema class:

```php
<?php

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class CreateUser
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50)]
    #[OA\Property(type: 'string', description: 'The name of the user', nullable: false)]
    private string $name;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[OA\Property(type: 'string', description: 'The email address of the user', nullable: false)]
    private string $email;

    // Getters and setters...
}
```

2. The bundle will automatically:
   - Understand the OA attributes for OpenAPI-based documentation in `tools/list`
   - Deserialize incoming JSON data into your schema class
   - Validate all constraints defined in your schema
   - Sanitize input data

This ensures that your tool handlers always receive properly validated and sanitized data.

### JSON-RPC Method Handlers

The bundle provides a robust system for handling JSON-RPC requests. By default, it includes three essential method handlers:

#### Built-in Method Handlers

1. **`initialize`**
   - Called when a client first connects to the server
   - Returns server information and capabilities
   - Essential for client-server handshake

2. **`tools/list`**
   - Lists all available tools on the server
   - Returns tool metadata including names, descriptions, and input schemas
   - Used by clients to discover available tools

3. **`tools/call`**
   - Executes a specific tool
   - Handles input validation and tool execution
   - Returns the tool's response

These methods are automatically registered and handled by the bundle. You don't need to implement them yourself.

#### Creating Custom Method Handlers

You can create your own JSON-RPC method handlers for additional functionality:

1. Create a new class that implements the `MethodHandlerInterface`
2. Use the `#[AsMethodHandler]` attribute to register your handler

Example:

```php
<?php

use Ecourty\McpServerBundle\Attribute\AsMethodHandler;
use Ecourty\McpServerBundle\MethodHandler\MethodHandlerInterface;

#[AsMethodHandler(
    method: 'my_method',
)]
class MyMethodHandler implements MethodHandlerInterface
{
    public function handle(JsonRpcRequest $params): array
    {
        // Your request handling logic here
        // ...

        return ['result' => 'success'];
    }
}
```

#### Method Handler Attributes

The `#[AsMethodHandler]` attribute supports:

- `method` (string, required): The JSON-RPC method name

### Developer Experience

The bundle provides several tools to help you during development:

#### Debug Command

The `debug:mcp-tools` command helps you inspect and debug your MCP tools:

```bash
# List all registered tools
php bin/console debug:mcp-tools

# Get detailed information about a specific tool
php bin/console debug:mcp-tools my_tool_name
```

This command is particularly useful for:
- Verifying tool registration
- Checking input schemas
- Validating tool annotations

## Contributing

Contributions to the MCP Server Bundle are welcome! Here's how you can help:

1. Fork the repository
2. Create a new branch for your feature
3. Make your changes
4. Submit a pull request

Please ensure your code follows our coding standards and includes appropriate tests.

### Development Setup

1. Fork and clone the repository
2. Install dependencies
```bash
composer install
```

3. Make your chages

3. Fix the code style and run PHPStan
```bash
composer fix-cs
composer phpstan
```

4. Run the tests
```bash
composer test
```

## License

This bundle is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
