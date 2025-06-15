<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\EventListener;

use Ecourty\McpServerBundle\Enum\McpErrorCode;
use Ecourty\McpServerBundle\Exception\MethodHandlerNotFoundException;
use Ecourty\McpServerBundle\Exception\RequestHandlingException;
use Ecourty\McpServerBundle\HttpFoundation\JsonRpcError;
use Ecourty\McpServerBundle\HttpFoundation\JsonRpcResponse;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Handles exceptions thrown during the request handling process.
 *
 * It converts exceptions into JSON-RPC error responses.
 */
#[AsEventListener(event: KernelEvents::EXCEPTION, method: 'onKernelException')]
class ExceptionListener
{
    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $jsonRpcRequestId = $request?->attributes->get('json_rpc_request_id');

        $exception = $event->getThrowable();

        $event->allowCustomResponseCode();

        if ($exception instanceof UnprocessableEntityHttpException) {
            $event->setResponse(new JsonResponse($this->buildErrorResponse(
                jsonRpcRequestId: $jsonRpcRequestId,
                errorCode: McpErrorCode::PARSE_ERROR,
            )));
            return;
        }

        if ($exception instanceof MethodHandlerNotFoundException) {
            $event->setResponse(new JsonResponse($this->buildErrorResponse(
                jsonRpcRequestId: $jsonRpcRequestId,
                errorCode: McpErrorCode::TOOL_NOT_FOUND,
            )));
            return;
        }

        if ($exception instanceof RequestHandlingException) {
            $event->setResponse(new JsonResponse($this->buildErrorResponse(
                jsonRpcRequestId: $jsonRpcRequestId,
                errorCode: McpErrorCode::INTERNAL_ERROR,
            )));
        }
    }

    private function buildErrorResponse(
        int|string|null $jsonRpcRequestId,
        McpErrorCode $errorCode,
    ): JsonRpcResponse {
        return new JsonRpcResponse(
            id: $jsonRpcRequestId,
            result: null,
            error: new JsonRpcError(code: $errorCode, message: $errorCode->getMessage()),
        );
    }
}
