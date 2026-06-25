<?php

namespace Foutraz\Strava\Actions;

use Foutraz\Strava\Dto\TokenResponse;
use Foutraz\Strava\Exceptions\ActionFailed;
use Foutraz\Strava\Exceptions\InvalidData;
use Foutraz\Strava\Exceptions\ResourceNotFound;
use Foutraz\Strava\Exceptions\TooManyRequestsException;
use Foutraz\Strava\Exceptions\Unauthorized;
use Foutraz\Strava\StravaManager;
use GuzzleHttp\Exception\GuzzleException;

class ManagesAuthentication extends StravaManager
{
    /**
     * @param  array<int, string>  $scopes
     */
    public function authorizeUrl(array $scopes = ['read', 'activity:read_all']): string
    {
        return 'https://www.strava.com/oauth/authorize?'.http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'approval_prompt' => 'auto',
            'scope' => implode(',', $scopes),
        ]);
    }

    /**
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws TooManyRequestsException
     * @throws Unauthorized
     */
    public function exchangeToken(string $code): TokenResponse
    {
        return TokenResponse::fromArray($this->post('https://www.strava.com/oauth/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $code,
            'grant_type' => 'authorization_code',
        ]));
    }

    /**
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws TooManyRequestsException
     * @throws Unauthorized
     */
    public function refreshToken(string $refreshToken): TokenResponse
    {
        return TokenResponse::fromArray($this->post('https://www.strava.com/oauth/token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ]));
    }

    /**
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws TooManyRequestsException
     * @throws Unauthorized
     */
    public function revokeToken(string $accessToken): mixed
    {
        return $this->post('https://www.strava.com/oauth/deauthorize', [
            'access_token' => $accessToken,
        ]);
    }
}
