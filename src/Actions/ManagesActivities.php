<?php

namespace Foutraz\Strava\Actions;

use Foutraz\Strava\Exceptions\ActionFailed;
use Foutraz\Strava\Exceptions\InvalidData;
use Foutraz\Strava\Exceptions\ResourceNotFound;
use Foutraz\Strava\Exceptions\Unauthorized;
use Foutraz\Strava\StravaManager;
use GuzzleHttp\Exception\GuzzleException;

class ManagesActivities extends StravaManager
{
    /**
     * @throws ResourceNotFound
     * @throws GuzzleException
     * @throws ActionFailed
     * @throws InvalidData
     * @throws Unauthorized
     */
    public function list()
    {
        return $this->get('/athlete/activities');
    }

    /**
     * @throws ResourceNotFound
     * @throws GuzzleException
     * @throws ActionFailed
     * @throws Unauthorized
     * @throws InvalidData
     */
    public function find(int $activityId)
    {
        return $this->get("/activities/$activityId");
    }

    /**
     * @throws ResourceNotFound
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws Unauthorized
     */
    public function streams(int $activityId): mixed
    {
        return $this->get("/activities/$activityId/streams");
    }

    /**
     * @throws ResourceNotFound
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws Unauthorized
     */
    public function laps(int $activityId)
    {
        return $this->get("/activities/$activityId/laps");
    }

    /**
     * @throws ResourceNotFound
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws Unauthorized
     */
    public function comments(int $activityId)
    {
        return $this->get("/activities/$activityId/comments");
    }

    /**
     * @throws ResourceNotFound
     * @throws GuzzleException
     * @throws ActionFailed
     * @throws InvalidData
     * @throws Unauthorized
     */
    public function kudos(int $activityId)
    {
        return $this->get("/activities/$activityId/kudos");
    }
}
