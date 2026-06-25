<?php

return [
    'client_id' => env('STRAVA_CLIENT_ID'),
    'client_secret' => env('STRAVA_CLIENT_SECRET'),
    'redirect_uri' => env('STRAVA_REDIRECT_URI'),
    'base_url' => env('STRAVA_BASE_URL', 'https://www.strava.com/api/v3'),
    'endpoint' => env('STRAVA_BASE_URL', 'https://www.strava.com/api/v3'),
    'token' => env('STRAVA_TOKEN'),
];
