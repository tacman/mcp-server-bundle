<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Tests\Service;

use Ecourty\McpServerBundle\Enum\McpErrorCode;
use Ecourty\McpServerBundle\Service\ResponseFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @coversDefaultClass \Ecourty\McpServerBundle\Service\ResponseFactory
 */
class ResponseFactoryTest extends KernelTestCase
{
    private ResponseFactory $responseFactory;

    protected function setUp(): void
    {
        /** @var ResponseFactory $responseFactory */
        $responseFactory = self::getContainer()->get(ResponseFactory::class);

        $this->responseFactory = $responseFactory;
    }

    /**
     * @covers ::success
     * @covers ::createResponse
     */
    public function testSuccessResponse(): void
    {
        $data = ['key' => 'value'];
        $response = $this->responseFactory->success(1, $data);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $parsedContent = json_decode((string) $response->getContent(), true);

        $expectedResponse = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'result' => [
                'key' => 'value',
            ],
        ];
        $this->assertSame($expectedResponse, $parsedContent);
    }

    /**
     * @covers ::error
     * @covers ::createResponse
     */
    public function testErrorResponse(): void
    {
        $errorCode = McpErrorCode::INVALID_REQUEST;
        $response = $this->responseFactory->error(1, $errorCode);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());

        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
        $parsedContent = json_decode((string) $response->getContent(), true);

        $expectedResponse = [
            'jsonrpc' => '2.0',
            'id' => 1,
            'error' => [
                'code' => $errorCode->value,
                'message' => $errorCode->getMessage(),
            ],
        ];
        $this->assertSame($expectedResponse, $parsedContent);
    }
}
