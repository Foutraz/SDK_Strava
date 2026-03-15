<?php

namespace Foutraz\Strava\Actions;

use Foutraz\Strava\Concerns\MakesHttpRequests;
use Foutraz\Strava\Exceptions\ActionFailed;
use Foutraz\Strava\Exceptions\InvalidData;
use Foutraz\Strava\Exceptions\ResourceNotFound;
use Foutraz\Strava\Exceptions\Unauthorized;
use Foutraz\Strava\StravaManager;
use GuzzleHttp\Exception\GuzzleException;

class ManagesAuthentication extends StravaManager
{
    use MakesHttpRequests;
    public function authorizeUrl(array $scopes = ['activity:read']): string
    {
        return 'https://www.strava.com/oauth/authorize?' . http_build_query([
                'client_id' => config('strava.client_id'),
                'redirect_uri' => config('strava.redirect_uri'),
                'response_type' => 'code',
                'approval_prompt' => 'auto',
                'scope' => implode(',', $scopes),
            ]);
    }

    /**
     * @throws ResourceNotFound
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws Unauthorized
     */
    public function exchangeToken(string $code)
    {
        return $this->post('https://www.strava.com/oauth/token', [
            'client_id' => config('strava.client_id'),
            'client_secret' => config('strava.client_secret'),
            'code' => $code,
            'grant_type' => 'authorization_code',
        ]);
    }

    /**
     * @throws ResourceNotFound
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws Unauthorized
     */
    public function refreshToken(string $refreshToken)
    {
        return $this->post('https://www.strava.com/oauth/token', [
            'client_id' => config('strava.client_id'),
            'client_secret' => config('strava.client_secret'),
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ]);
    }

    /**
     * @throws ResourceNotFound
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws Unauthorized
     */
    public function revokeToken(string $accessToken)
    {
        return $this->post('https://www.strava.com/oauth/deauthorize', [
            'access_token' => $accessToken,
        ]);
    }
}