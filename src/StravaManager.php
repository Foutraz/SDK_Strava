<?php

namespace Foutraz\Strava;

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
        public ?ClientInterface $client = null
    ) {
        $this->client ??= new Client([
            'http_errors' => false,
            'base_uri' => $this->endpoint,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.$this->apiToken,
            ],
        ]);
    }

    public function activities(): ManagesActivities
    {
        return new ManagesActivities($this->endpoint, $this->apiToken, $this->client);
    }

    public function athletes(): ManagesAthletes
    {
        return new ManagesAthletes($this->endpoint, $this->apiToken, $this->client);
    }

    public function gears(): ManagesGears
    {
        return new ManagesGears($this->endpoint, $this->apiToken, $this->client);
    }
}
