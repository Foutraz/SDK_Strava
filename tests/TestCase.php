<?php

namespace Foutraz\Strava\Tests;

use Foutraz\Strava\StravaManager;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @var array<int, array<string, mixed>>
     */
    protected array $history = [];

    /**
     * Builds a StravaManager whose Guzzle client replays the queued responses.
     *
     * @param  array<int, Response>  $responses
     */
    protected function managerWithResponses(array $responses): StravaManager
    {
        $mock = new MockHandler($responses);
        $stack = HandlerStack::create($mock);
        $stack->push(Middleware::history($this->history));

        $client = new Client([
            'handler' => $stack,
            'http_errors' => false,
            'base_uri' => 'https://www.strava.com/api/v3/',
        ]);

        return new StravaManager(
            'https://www.strava.com/api/v3',
            'access-token',
            'client-id',
            'client-secret',
            'https://example.test/callback',
            $client,
        );
    }

    /**
     * Builds a JSON-bodied Guzzle response.
     *
     * @param  array<mixed>  $body
     */
    protected function jsonResponse(int $status, array $body): Response
    {
        return new Response($status, ['Content-Type' => 'application/json'], (string) json_encode($body));
    }

    protected function lastRequestUri(): string
    {
        $request = end($this->history)['request'];

        return (string) $request->getUri();
    }
}
