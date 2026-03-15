<?php

namespace Foutraz\Strava\Facades;

use Illuminate\Support\Facades\Facade;

class Strava extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'strava';
    }
}
