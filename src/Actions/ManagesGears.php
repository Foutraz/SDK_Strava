<?php

namespace Foutraz\Strava\Actions;

use Foutraz\Strava\Dto\Gear;
use Foutraz\Strava\Exceptions\ActionFailed;
use Foutraz\Strava\Exceptions\InvalidData;
use Foutraz\Strava\Exceptions\ResourceNotFound;
use Foutraz\Strava\Exceptions\TooManyRequestsException;
use Foutraz\Strava\Exceptions\Unauthorized;
use Foutraz\Strava\StravaManager;
use GuzzleHttp\Exception\GuzzleException;

class ManagesGears extends StravaManager
{
    /**
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws TooManyRequestsException
     * @throws Unauthorized
     */
    public function find(string $gearId): Gear
    {
        return Gear::fromArray($this->get("gear/$gearId"));
    }
}
