<?php

namespace Foutraz\Strava\Actions;

use Foutraz\Strava\Concerns\MakesHttpRequests;
use Foutraz\Strava\StravaManager;
use GuzzleHttp\Exception\GuzzleException;

class ManagesActivities extends StravaManager
{
    use MakesHttpRequests;
}
