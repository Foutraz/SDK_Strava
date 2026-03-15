<?php

namespace Foutraz\Strava\Actions;

use Foutraz\Strava\Concerns\MakesHttpRequests;
use Foutraz\Strava\Exceptions\ActionFailed;
use Foutraz\Strava\Exceptions\InvalidData;
use Foutraz\Strava\Exceptions\ResourceNotFound;
use Foutraz\Strava\Exceptions\Unauthorized;
use Foutraz\Strava\StravaManager;
use GuzzleHttp\Exception\GuzzleException;

class ManagesAthletes extends StravaManager
{
    use MakesHttpRequests;

    /**
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws Unauthorized
     */
    public function me(): mixed
    {
        return $this->get('/athlete');
    }

    /**
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws Unauthorized
     */
    public function stats(int $athleteId): mixed
    {
        return $this->get("/athletes/$athleteId/stats");
    }
}
