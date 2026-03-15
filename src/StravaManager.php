<?php

namespace Foutraz\Strava;

use Foutraz\Strava\Actions\ManagesAuthentication;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Foutraz\Strava\Actions\ManagesActivities;
use Foutraz\Strava\Actions\ManagesAthletes;
use Foutraz\Strava\Actions\ManagesGears;
use Foutraz\Strava\Concerns\MakesHttpRequests;

class StravaManager
{
    use MakesHttpRequests;

    public function __construct(
        public string $endpoint,
        public string $apiToken,
        public string $clientId,
        public string $clientSecret,
        public string $redirectUri,
        public ?ClientInterface $client = null
    ) {
        $this->client ??= new Client([
            'http_errors' => false,
            'base_uri' => $this->endpoint,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$this->apiToken,
            ],
        ]);
    }

    public function auth(): ManagesAuthentication
    {
        return new ManagesAuthentication($this->endpoint, $this->apiToken, $this->clientId, $this->clientSecret, $this->redirectUri, $this->client);
    }

    public function activities(): ManagesActivities
    {
        return new ManagesActivities($this->endpoint, $this->apiToken, $this->clientId, $this->clientSecret, $this->redirectUri, $this->client);
    }

    public function athletes(): ManagesAthletes
    {
        return new ManagesAthletes($this->endpoint, $this->apiToken, $this->clientId, $this->clientSecret, $this->redirectUri, $this->client);
    }

    public function gears(): ManagesGears
    {
        return new ManagesGears($this->endpoint, $this->apiToken, $this->clientId, $this->clientSecret, $this->redirectUri, $this->client);
    }

    public function setToken(string $token): static
    {
        $this->apiToken = $token;

        $this->client = new Client([
            'http_errors' => false,
            'base_uri' => $this->endpoint,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return $this;
    }
}
