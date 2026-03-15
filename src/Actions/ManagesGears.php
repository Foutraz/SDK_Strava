<?php

namespace Foutraz\Strava\Actions;

use Foutraz\Strava\StravaManager;
use GuzzleHttp\Exception\GuzzleException;
use Foutraz\Strava\Concerns\MakesHttpRequests;

class ManagesGears extends StravaManager
{
    use MakesHttpRequests;
}
