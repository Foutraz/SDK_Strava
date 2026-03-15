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

        $this->app->singleton(StravaManager::class, function ($app) {

            $config = $app['config']['strava'];

            if (blank($config['endpoint'])) {
                throw new RuntimeException(
                    'No Strava API endpoint was provided.'
                );
            }

            return new StravaManager(
                $config['endpoint'],
                $config['token'],
                $config['client_id'],
                $config['client_secret'],
                $config['redirect_uri'],
            );
        });

        $this->app->alias(StravaManager::class, 'strava');
    }

    public function boot(): void
    {
        $this->publishes([
            dirname(__DIR__, 2).'/config/strava.php' =>
                $this->app->configPath('strava.php'),
        ], 'strava-config');
    }
}