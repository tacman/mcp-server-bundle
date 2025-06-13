<?php

declare(strict_types=1);

namespace Ecourty\McpServerBundle\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;

class WebTestCase extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        static::ensureKernelShutdown();
        $this->client = static::createClient();
    }

    protected function request(
        string $method,
        string $url,
        array $body = [],
        array $parameters = [],
        int $expectedHttpCode = Response::HTTP_OK,
    ): Response {
        if (isset($this->client) === false) {
            throw new \LogicException('Client not initialized.');
        }

        $this->client->request(
            method: $method,
            uri: $url,
            parameters: $parameters,
            server: [
                'CONTENT_TYPE' => 'application/json',
            ],
            content: (string) json_encode($body),
        );
        $response = $this->client->getResponse();

        $this->assertSame(
            $expectedHttpCode,
            $response->getStatusCode(),
            \sprintf('Expected HTTP code %d, got %d. Response: %s', $expectedHttpCode, $response->getStatusCode(), $response->getContent()),
        );

        return $response;
    }
}
