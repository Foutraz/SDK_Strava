<?php

namespace Foutraz\Strava\Actions;

use Foutraz\Strava\Dto\Athlete;
use Foutraz\Strava\Dto\AthleteStats;
use Foutraz\Strava\Exceptions\ActionFailed;
use Foutraz\Strava\Exceptions\InvalidData;
use Foutraz\Strava\Exceptions\ResourceNotFound;
use Foutraz\Strava\Exceptions\TooManyRequestsException;
use Foutraz\Strava\Exceptions\Unauthorized;
use Foutraz\Strava\StravaManager;
use GuzzleHttp\Exception\GuzzleException;

class ManagesAthletes extends StravaManager
{
    /**
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws TooManyRequestsException
     * @throws Unauthorized
     */
    public function me(): Athlete
    {
        return Athlete::fromArray($this->get('athlete'));
    }

    /**
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws TooManyRequestsException
     * @throws Unauthorized
     */
    public function stats(int $athleteId): AthleteStats
    {
        return AthleteStats::fromArray($this->get("athletes/$athleteId/stats"));
    }
}
