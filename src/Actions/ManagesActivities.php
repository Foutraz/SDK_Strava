<?php

namespace Foutraz\Strava\Actions;

use Foutraz\Strava\Dto\Activity;
use Foutraz\Strava\Dto\ActivityStream;
use Foutraz\Strava\Exceptions\ActionFailed;
use Foutraz\Strava\Exceptions\InvalidData;
use Foutraz\Strava\Exceptions\ResourceNotFound;
use Foutraz\Strava\Exceptions\TooManyRequestsException;
use Foutraz\Strava\Exceptions\Unauthorized;
use Foutraz\Strava\StravaManager;
use Generator;
use GuzzleHttp\Exception\GuzzleException;

class ManagesActivities extends StravaManager
{
    /**
     * @param  array<string, mixed>  $params
     * @return array<int, Activity>
     *
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws TooManyRequestsException
     * @throws Unauthorized
     */
    public function list(array $params = []): array
    {
        $response = $this->get('athlete/activities', $params);

        return array_map(static fn (array $activity): Activity => Activity::fromArray($activity), $response);
    }

    /**
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws TooManyRequestsException
     * @throws Unauthorized
     */
    public function find(int $activityId): Activity
    {
        return Activity::fromArray($this->get("activities/$activityId"));
    }

    /**
     * @return array<int, ActivityStream>
     *
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws TooManyRequestsException
     * @throws Unauthorized
     */
    public function streams(int $activityId): array
    {
        return ActivityStream::collectionFromArray($this->get("activities/$activityId/streams"));
    }

    /**
     * @return Generator<int, Activity>
     *
     * @throws ActionFailed
     * @throws GuzzleException
     * @throws InvalidData
     * @throws ResourceNotFound
     * @throws TooManyRequestsException
     * @throws Unauthorized
     */
    public function iterate(int $perPage = 200, ?int $after = null): Generator
    {
        $page = 1;

        while (true) {
            $query = ['page' => $page, 'per_page' => $perPage];

            if ($after !== null) {
                $query['after'] = $after;
            }

            $activities = $this->get('athlete/activities', $query);

            foreach ($activities as $activity) {
                yield Activity::fromArray($activity);
            }

            if (count($activities) < $perPage) {
                break;
            }

            $page++;
        }
    }
}
