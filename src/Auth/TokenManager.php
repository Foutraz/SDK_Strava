<?php

namespace Foutraz\Strava\Auth;

use Foutraz\Strava\Exceptions\ActionFailed;
use Foutraz\Strava\Exceptions\InvalidData;
use Foutraz\Strava\Exceptions\ResourceNotFound;
use Foutraz\Strava\Exceptions\Unauthorized;
use Foutraz\Strava\StravaManager;
use GuzzleHttp\Exception\GuzzleException;

class TokenManager
{
    public function __construct(
        protected StravaManager $manager
    ) {}

    /**
     * @throws ResourceNotFound
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws Unauthorized
     */
    public function ensureValidToken(array $tokenData): string
    {
        if ($tokenData['expires_at'] < time()) {

            $response = $this->manager
                ->auth()
                ->refreshToken($tokenData['refresh_token']);

            return $response['access_token'];
        }

        return $tokenData['access_token'];
    }
}