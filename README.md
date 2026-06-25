# foutraz/strava

A framework-agnostic PHP SDK for the [Strava API v3](https://developers.strava.com/), with first-class Laravel integration (ServiceProvider + Facade). Built on Guzzle, it returns typed DTOs, maps HTTP errors to named exceptions, and handles OAuth token exchange/refresh.

## Requirements

- PHP `^8.4`
- `guzzlehttp/guzzle` `^7.0`
- `illuminate/support` `^11.0|^12.0` (Laravel integration only)

## Installation

```bash
composer require foutraz/strava
```

## Environment variables

| Variable | Description | Default |
| --- | --- | --- |
| `STRAVA_CLIENT_ID` | OAuth application client id | — |
| `STRAVA_CLIENT_SECRET` | OAuth application client secret | — |
| `STRAVA_REDIRECT_URI` | OAuth callback URL registered with Strava | — |
| `STRAVA_BASE_URL` | API base endpoint | `https://www.strava.com/api/v3` |
| `STRAVA_TOKEN` | Default athlete access token | — |

In Laravel, the `StravaServiceProvider` is auto-discovered and binds `StravaManager` as a singleton resolvable via the `Strava` facade.

## Building a manager

### Laravel

```php
use Foutraz\Strava\StravaManager;

$strava = app(StravaManager::class);
```

### Standalone

```php
use Foutraz\Strava\StravaManager;

$strava = new StravaManager(
    endpoint: 'https://www.strava.com/api/v3',
    apiToken: $accessToken,
    clientId: $clientId,
    clientSecret: $clientSecret,
    redirectUri: $redirectUri,
);
```

## OAuth flow

### 1. Redirect the user to the authorization screen

```php
$url = $strava->auth()->authorizeUrl(); // default scope ['read', 'activity:read_all']
$url = $strava->auth()->authorizeUrl(['read', 'activity:read_all', 'profile:read_all']);
```

### 2. Exchange the callback code for tokens

```php
use Foutraz\Strava\Dto\TokenResponse;

$token = $strava->auth()->exchangeToken($_GET['code']); // TokenResponse

$token->accessToken;
$token->refreshToken;
$token->expiresAt;   // unix timestamp
$token->athlete?->id;
```

### 3. Keep the token fresh

`TokenManager` refreshes when the token is within a 60-second skew of expiry and returns the (possibly rotated) refresh token to persist.

```php
use Foutraz\Strava\Auth\TokenManager;

$token = (new TokenManager($strava))->ensureValidToken([
    'access_token' => $stored['access_token'],
    'refresh_token' => $stored['refresh_token'],
    'expires_at' => $stored['expires_at'],
]);

$strava->setToken($token->accessToken);
// Persist $token->refreshToken and $token->expiresAt.
```

## Activities

```php
// Single page with query filters.
$activities = $strava->activities()->list(['per_page' => 50, 'page' => 1]);

// A single activity.
$activity = $strava->activities()->find(123456789);

// Activity streams.
$streams = $strava->activities()->streams(123456789);

// Lazily iterate every activity, page by page.
foreach ($strava->activities()->iterate(perPage: 200, after: $sinceTimestamp) as $activity) {
    $activity->name;
    $activity->distance;        // metres
    $activity->movingTime;      // seconds
    $activity->startDate;       // DateTimeImmutable|null
    $activity->mapPolyline;     // ?string
}
```

## Athletes & gear

```php
$athlete = $strava->athletes()->me();          // Athlete
$stats = $strava->athletes()->stats($athlete->id); // AthleteStats
$stats->allRunTotals->distance;

$gear = $strava->gears()->find('b1234567');    // Gear
```

## DTOs

All DTOs live under `Foutraz\Strava\Dto` and expose a static `fromArray(array): self` factory mapping snake_case payloads to camelCase typed properties: `Activity`, `Athlete`, `Gear`, `ActivityStream` (+ `collectionFromArray`), `AthleteStats` (with nested `TotalsSummary`), and `TokenResponse`.

## Error handling

HTTP errors are mapped to named exceptions under `Foutraz\Strava\Exceptions`:

| Status | Exception |
| --- | --- |
| 401 | `Unauthorized` |
| 404 | `ResourceNotFound` |
| 422 | `InvalidData` |
| 429 | `TooManyRequestsException` |
| other 4xx/5xx | `ActionFailed` |

## Testing

```bash
composer test
```
