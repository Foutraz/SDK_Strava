<?php

namespace Foutraz\Strava\Concerns;

use Exception;
use Generator;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Foutraz\Strava\Exceptions\ActionFailed;
use Foutraz\Strava\Exceptions\InvalidData;
use Foutraz\Strava\Exceptions\ResourceNotFound;
use Foutraz\Strava\Exceptions\Unauthorized;

trait MakesHttpRequests
{
    /**
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws Unauthorized
     */
    public function get(string $uri): mixed
    {
        return $this->request('GET', $uri);
    }

    /**
     * @throws ResourceNotFound
     * @throws Unauthorized
     * @throws GuzzleException
     * @throws ActionFailed
     * @throws InvalidData
     */
    public function post(string $uri, array $payload = [])
    {
        return $this->request('POST', $uri, $payload);
    }

    /**
     * @throws ResourceNotFound
     * @throws Unauthorized
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ActionFailed
     */
    public function put(string $uri, array $payload = [])
    {
        return $this->request('PUT', $uri, $payload);
    }

    /**
     * @throws ResourceNotFound
     * @throws Unauthorized
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ActionFailed
     */
    public function patch(string $uri, array $payload = [])
    {
        return $this->request('PATCH', $uri, $payload);
    }

    /**
     * @throws ResourceNotFound
     * @throws Unauthorized
     * @throws GuzzleException
     * @throws ActionFailed
     * @throws InvalidData
     */
    public function delete(string $uri, array $payload = [])
    {
        return $this->request('DELETE', $uri, $payload);
    }

    /**
     * @throws ResourceNotFound
     * @throws Unauthorized
     * @throws GuzzleException
     * @throws ActionFailed
     * @throws InvalidData
     */
    public function request(string $verb, string $uri, array $payload = []): mixed
    {
        $response = $this->client->request(
            $verb,
            $uri,
            empty($payload) ? [] : ['json' => $payload]
        );

        if (! $this->isSuccessful($response)) {
            $this->handleRequestError($response);
        }

        $responseBody = (string) $response->getBody();

        $decoded = json_decode($responseBody, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $responseBody;
    }

    public function isSuccessful($response): bool
    {
        if (! $response) {
            return false;
        }

        return (int) substr($response->getStatusCode(), 0, 1) === 2;
    }

    protected function buildFilterString(array $filters): string
    {
        if (count($filters) === 0) {
            return '';
        }

        $preparedFilters = [];
        foreach ($filters as $name => $value) {
            $preparedFilters["filter[$name]"] = $value;
        }

        return '?'.http_build_query($preparedFilters);
    }

    /**
     * @throws ActionFailed
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws Unauthorized
     * @throws Exception
     */
    protected function handleRequestError(ResponseInterface $response): void
    {
        if ($response->getStatusCode() === 422) {
            throw new InvalidData(json_decode((string) $response->getBody(), true));
        }

        if ($response->getStatusCode() === 404) {
            throw new ResourceNotFound;
        }

        if ($response->getStatusCode() === 400) {
            throw new ActionFailed((string) $response->getBody());
        }

        if ($response->getStatusCode() === 401) {
            throw new Unauthorized((string) $response->getBody());
        }

        throw new Exception((string) $response->getBody());
    }

    /**
     * @return Generator<int, array>
     *
     * @throws ActionFailed
     */
    protected function paginate(callable $fetch, int $limit = 200, int $startOffset = 0): Generator
    {
        $limit = min(max($limit, 1), 200);
        $offset = max($startOffset, 0);

        while (true) {
            $resp = $fetch($limit, $offset);

            if (! is_array($resp)) {
                return;
            }

            $data = $resp['data'] ?? [];
            if (! is_array($data)) {
                throw new ActionFailed('Invalid pagination payload: "data" must be an array.');
            }

            foreach ($data as $item) {
                if (is_array($item)) {
                    yield $item;
                }
            }

            $nextOffset = $resp['pagination']['nextOffset'] ?? null;
            if ($nextOffset === null) {
                break;
            }

            $nextOffset = (int) $nextOffset;
            if ($nextOffset <= $offset) {
                break;
            }

            $offset = $nextOffset;
        }
    }
}
