<?php

namespace Foutraz\Strava\Actions;

use Foutraz\Strava\Concerns\MakesHttpRequests;
use Foutraz\Strava\StravaManager;

class ManagesAuthentication extends StravaManager
{
    use MakesHttpRequests;
    public function execute(string $code): array
    {
        return $this->post('/oauth/token', [
            'client_id' => config('strava.client_id'),
            'client_secret' => config('strava.client_secret'),
            'code' => $code,
            'grant_type' => 'authorization_code',
        ]);
    }
}