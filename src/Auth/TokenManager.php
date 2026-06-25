<?php

namespace Foutraz\Strava\Auth;

use Foutraz\Strava\Dto\TokenResponse;
use Foutraz\Strava\Exceptions\ActionFailed;
use Foutraz\Strava\Exceptions\InvalidData;
use Foutraz\Strava\Exceptions\ResourceNotFound;
use Foutraz\Strava\Exceptions\TooManyRequestsException;
use Foutraz\Strava\Exceptions\Unauthorized;
use Foutraz\Strava\StravaManager;
use GuzzleHttp\Exception\GuzzleException;

class TokenManager
{
    public function __construct(
        protected StravaManager $manager
    ) {}

    /**
     * @param  array{access_token: string, refresh_token: string, expires_at: int}  $tokenData
     *
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws TooManyRequestsException
     * @throws Unauthorized
     */
    public function ensureValidToken(array $tokenData): TokenResponse
    {
        if ($this->isExpired((int) $tokenData['expires_at'])) {
            return $this->manager->auth()->refreshToken($tokenData['refresh_token']);
        }

        return new TokenResponse(
            $tokenData['access_token'],
            $tokenData['refresh_token'],
            (int) $tokenData['expires_at'],
            (int) $tokenData['expires_at'] - time(),
            'Bearer',
            null,
        );
    }

    public function isExpired(int $expiresAt): bool
    {
        return $expiresAt - 60 < time();
    }
}
