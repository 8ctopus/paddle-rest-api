<?php

declare(strict_types=1);

namespace Oct8pus\Paddle;

use Oct8pus\Paddle\Auth;

abstract class RestBase
{
    protected readonly string $baseUri;
    protected readonly HttpHandler $handler;
    private ?Auth $auth;

    /**
     * Constructor
     *
     * @param bool        $sandbox
     * @param HttpHandler $handler
     * @param Auth        $auth
     */
    public function __construct(bool $sandbox, HttpHandler $handler, ?Auth $auth)
    {
        $this->baseUri = $sandbox ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';
        $this->handler = $handler;
        $this->auth = $auth;
    }

    /**
     * Send request
     *
     * @param string        $method
     * @param string        $uri
     * @param array<string> $headers
     * @param ?string       $body
     * @param array|int     $expectedStatus
     *
     * @return string
     *
     * @throws PaddleException
     */
    protected function sendRequest(string $method, string $uri, array $headers, ?string $body, array|int $expectedStatus) : string
    {
        $request = $this->handler->createRequest($method, $this->baseUri . $uri, array_merge($this->headers(), $headers), $body);

        $response = $this->handler->sendRequest($request);

        return $this->handler->processResponse($response, $expectedStatus);
    }

    /**
     * Send json request
     *
     * @param string        $method
     * @param string        $uri
     * @param array<string> $headers
     * @param array         $json
     * @param array|int     $expectedStatus
     *
     * @return string
     *
     * @throws PaddleException
     */
    protected function sendJsonRequest(string $method, string $uri, array $headers, array $json, array|int $expectedStatus) : string
    {
        $body = json_encode($json, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);

        return $this->sendRequest($method, $uri, $headers, $body, $expectedStatus);
    }

    /**
     * Decode response
     *
     * @param string $response
     *
     * @return array
     *
     * @throws JSONException|ValueError
     */
    protected function decodeResponse(string $response) : array
    {
        return json_decode($response, true, 512, JSON_THROW_ON_ERROR)['data'];
    }

    /**
     * Get headers
     *
     * @return array<string, string>
     */
    protected function headers() : array
    {
        return [
            'Authorization' => 'Bearer ' . $this->auth?->token(),
            'Content-Type' => 'application/json',
        ];
    }
}
