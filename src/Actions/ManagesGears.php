<?php

namespace Foutraz\Strava\Actions;

use Foutraz\Strava\Exceptions\ActionFailed;
use Foutraz\Strava\Exceptions\InvalidData;
use Foutraz\Strava\Exceptions\ResourceNotFound;
use Foutraz\Strava\Exceptions\Unauthorized;
use Foutraz\Strava\StravaManager;
use GuzzleHttp\Exception\GuzzleException;
use Foutraz\Strava\Concerns\MakesHttpRequests;

class ManagesGears extends StravaManager
{
    //TODO use Dto in actions
    /**
     * @throws ResourceNotFound
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws Unauthorized
     */
    public function find(string $gearId)
    {
        return $this->get("/gear/$gearId");
    }
}
