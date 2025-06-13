<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\MethodHandler;

use Ecourty\McpServerBundle\Attribute\AsMethodHandler;
use Ecourty\McpServerBundle\Contract\MethodHandlerInterface;
use Ecourty\McpServerBundle\Exception\ToolCallException;
use Ecourty\McpServerBundle\HttpFoundation\JsonRpcRequest;
use Ecourty\McpServerBundle\IO\ToolResponse;
use Ecourty\McpServerBundle\Service\InputSanitizer;
use Ecourty\McpServerBundle\Service\ToolRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsMethodHandler(methodName: 'tools/call')]
class ToolsCallMethodHandler implements MethodHandlerInterface
{
    public function __construct(
        private readonly ToolRegistry $toolRegistry,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly InputSanitizer $inputSanitizer,
    ) {
    }

    public function handle(JsonRpcRequest $request): array
    {
        $toolName = $request->params['name'] ?? null;

        if ($toolName === null) {
            throw new \InvalidArgumentException('Tool name is required.');
        }

        $tool = $this->toolRegistry->getTool($toolName);
        $toolDefinition = $this->toolRegistry->getToolDefinition($toolName);

        if ($tool === null) {
            throw new \InvalidArgumentException(\sprintf('Tool "%s" not found.', $request->params['name']));
        }

        if ($toolDefinition === null) {
            throw new \InvalidArgumentException(\sprintf(
                'Tool "%s" does not have a definition.',
                $toolName,
            ));
        }

        try {
            $arguments = $request->params['arguments'] ?? [];
            $sanitizedInput = $this->inputSanitizer->sanitize($arguments);

            $jsonPayload = json_encode($sanitizedInput, \JSON_THROW_ON_ERROR);
            $inputSchemaClass = $toolDefinition->inputSchemaClass;

            $inputModel = $this->serializer->deserialize(
                data: $jsonPayload,
                type: $inputSchemaClass,
                format: 'json',
            );

            if ($inputModel instanceof $inputSchemaClass === false) {
                throw new \InvalidArgumentException(\sprintf(
                    'Deserialized result is not an instance of "%s".',
                    $inputSchemaClass,
                ));
            }

            $violations = $this->validator->validate($inputModel);
            if ($violations->count() > 0) {
                return $this->buildValidationErrorResponse(iterator_to_array($violations));
            }

            if (method_exists($tool, '__invoke') === false) {
                throw new \LogicException(\sprintf('Tool "%s" does not implement __invoke method.', $toolName));
            }

            $toolResponse = $tool->__invoke($inputModel);
        } catch (\Throwable $exception) {
            throw new ToolCallException(
                message: 'An error occurred while handling a tool call.',
                code: Response::HTTP_INTERNAL_SERVER_ERROR,
                previous: $exception,
            );
        }

        return $this->buildResponse($toolResponse);
    }

    private function buildResponse(ToolResponse $toolResponse): array
    {
        $response = [];

        if ($toolResponse->isError === true) {
            $response['isError'] = true;
        }

        $response['content'] = $toolResponse->content;

        return $response;
    }

    private function buildValidationErrorResponse(array $errors): array
    {
        return [
            'isError' => true,
            'content' => array_map(function (ConstraintViolationInterface $violation) {
                return [
                    'type' => 'text',
                    'text' => \sprintf(
                        'Validation error: %s - %s',
                        $violation->getPropertyPath(),
                        $violation->getMessage(),
                    ),
                ];
            }, $errors),
        ];
    }
}
