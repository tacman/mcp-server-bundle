<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Controller;

use Ecourty\McpServerBundle\Exception\MethodHandlerNotFoundException;
use Ecourty\McpServerBundle\Exception\RequestHandlingException;
use Ecourty\McpServerBundle\HttpFoundation\JsonRpcRequest;
use Ecourty\McpServerBundle\Service\MethodHandlerRegistry;
use Ecourty\McpServerBundle\Service\ResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

/**
 * This controller handles incoming JSON-RPC requests.
 *
 * It maps the request payload to a JsonRpcRequest object, retrieves the appropriate
 * method handler from the registry, and returns a JsonRpcResponse.
 */
#[Route(name: 'mcp_server_')]
class EntrypointController extends AbstractController
{
    public function __construct(
        private readonly MethodHandlerRegistry $methodHandlerRegistry,
        private readonly ResponseFactory $responseFactory,
    ) {
    }

    #[Route(path: '', name: 'entrypoint', methods: [Request::METHOD_POST])]
    public function __invoke(
        #[MapRequestPayload] JsonRpcRequest $jsonRpcRequest,
        Request $request,
    ): Response {
        $request->attributes->set('json_rpc_request_id', $jsonRpcRequest->id);

        $requestHandler = $this->methodHandlerRegistry->getMethodHandler($jsonRpcRequest->method);

        if ($requestHandler === null) {
            throw new MethodHandlerNotFoundException();
        }

        try {
            $response = $requestHandler->handle($jsonRpcRequest);
        } catch (\Throwable $exception) {
            throw new RequestHandlingException($exception);
        }

        return $this->responseFactory->success(
            id: $jsonRpcRequest->id,
            result: $response,
        );
    }
}
