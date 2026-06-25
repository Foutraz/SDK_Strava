<?php

namespace Foutraz\Strava\Tests\Unit\Dto;

use Foutraz\Strava\Dto\Activity;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ActivityTest extends TestCase
{
    #[Test]
    public function it_maps_a_full_payload(): void
    {
        $activity = Activity::fromArray([
            'id' => 123,
            'name' => 'Morning Run',
            'distance' => 5000.5,
            'moving_time' => 1800,
            'elapsed_time' => 1900,
            'total_elevation_gain' => 42.0,
            'type' => 'Run',
            'sport_type' => 'TrailRun',
            'start_date' => '2024-01-02T08:00:00Z',
            'start_date_local' => '2024-01-02T09:00:00Z',
            'average_speed' => 2.78,
            'max_speed' => 4.1,
            'average_heartrate' => 150.0,
            'max_heartrate' => 175.0,
            'kilojoules' => 320.5,
            'gear_id' => 'g123',
            'start_latlng' => [48.85, 2.35],
            'end_latlng' => [48.86, 2.36],
            'map' => ['summary_polyline' => 'abc123'],
        ]);

        $this->assertSame(123, $activity->id);
        $this->assertSame('Morning Run', $activity->name);
        $this->assertSame(5000.5, $activity->distance);
        $this->assertSame(1800, $activity->movingTime);
        $this->assertSame(1900, $activity->elapsedTime);
        $this->assertSame(42.0, $activity->totalElevationGain);
        $this->assertSame('Run', $activity->type);
        $this->assertSame('TrailRun', $activity->sportType);
        $this->assertSame('2024-01-02T08:00:00+00:00', $activity->startDate?->format('c'));
        $this->assertSame('2024-01-02T09:00:00+00:00', $activity->startDateLocal?->format('c'));
        $this->assertSame(2.78, $activity->averageSpeed);
        $this->assertSame(4.1, $activity->maxSpeed);
        $this->assertSame(150.0, $activity->averageHeartrate);
        $this->assertSame(175.0, $activity->maxHeartrate);
        $this->assertSame(320.5, $activity->kilojoules);
        $this->assertSame('g123', $activity->gearId);
        $this->assertSame([48.85, 2.35], $activity->startLatlng);
        $this->assertSame([48.86, 2.36], $activity->endLatlng);
        $this->assertSame('abc123', $activity->mapPolyline);
    }

    #[Test]
    public function it_defaults_nullable_fields_to_null(): void
    {
        $activity = Activity::fromArray([
            'id' => 1,
            'name' => 'Ride',
            'distance' => 100.0,
            'moving_time' => 60,
            'elapsed_time' => 70,
            'total_elevation_gain' => 0.0,
            'type' => 'Ride',
        ]);

        $this->assertSame('Ride', $activity->sportType);
        $this->assertNull($activity->startDate);
        $this->assertNull($activity->averageHeartrate);
        $this->assertNull($activity->maxHeartrate);
        $this->assertNull($activity->kilojoules);
        $this->assertNull($activity->gearId);
        $this->assertNull($activity->startLatlng);
        $this->assertNull($activity->endLatlng);
        $this->assertNull($activity->mapPolyline);
    }
}
