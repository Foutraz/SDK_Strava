<?php

namespace Foutraz\Strava\Providers;

use Foutraz\Strava\StravaManager;
use Illuminate\Support\ServiceProvider;
use RuntimeException;

class StravaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__, 2).'/config/strava.php',
            'strava'
        );

        $this->app->singleton(StravaManager::class, function () {
            if (blank(config('strava.token'))) {
                throw new RuntimeException(
                    'No Strava API token was provided. Please provide an API token in the `strava.token` config key.'
                );
            }

            if (blank(config('strava.endpoint'))) {
                throw new RuntimeException(
                    'No Strava API endpoint was provided. Please provide an endpoint in the `strava.endpoint` config key.'
                );
            }

            return new StravaManager(
                config('strava.endpoint'),
                config('strava.token'),
            );
        });

        $this->app->singleton('strava', function ($app) {
            return $app->make(StravaManager::class);
        });
    }
}
