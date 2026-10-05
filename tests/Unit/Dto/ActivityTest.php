<?php

namespace Foutraz\Strava\Tests\Unit\Dto;

use DateTimeImmutable;
use Foutraz\Strava\Dto\Activity;
use PHPUnit\Framework\Attributes\DataProvider;
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
    public function it_maps_a_realistic_strava_payload_to_every_property(): void
    {
        $activity = Activity::fromArray([
            'resource_state' => 2,
            'athlete' => ['id' => 134815, 'resource_state' => 1],
            'name' => 'Happy Friday',
            'distance' => 24931.4,
            'moving_time' => 4500,
            'elapsed_time' => 4500,
            'total_elevation_gain' => 516.0,
            'type' => 'Ride',
            'sport_type' => 'MountainBikeRide',
            'workout_type' => null,
            'id' => 154504250376823,
            'external_id' => 'garmin_push_12345678987654321',
            'upload_id' => 987654321234567891,
            'upload_id_str' => '987654321234567891',
            'start_date' => '2018-05-02T12:15:09Z',
            'start_date_local' => '2018-05-02T05:15:09Z',
            'timezone' => '(GMT-08:00) America/Los_Angeles',
            'utc_offset' => -25200.0,
            'start_latlng' => [37.83, -122.26],
            'end_latlng' => [37.83, -122.26],
            'achievement_count' => 0,
            'kudos_count' => 3,
            'comment_count' => 1,
            'athlete_count' => 1,
            'photo_count' => 0,
            'map' => ['id' => 'a12345678987654321', 'summary_polyline' => 'abc123', 'resource_state' => 2],
            'trainer' => true,
            'commute' => false,
            'manual' => false,
            'private' => false,
            'flagged' => true,
            'gear_id' => 'b12345678987654321',
            'average_speed' => 5.54,
            'max_speed' => 11.0,
            'average_heartrate' => 140.3,
            'max_heartrate' => 178.0,
            'kilojoules' => 890.4,
            'device_name' => 'Garmin Edge 1030',
        ]);

        $this->assertSame(154504250376823, $activity->id);
        $this->assertSame('Happy Friday', $activity->name);
        $this->assertSame(24931.4, $activity->distance);
        $this->assertSame(4500, $activity->movingTime);
        $this->assertSame(4500, $activity->elapsedTime);
        $this->assertSame(516.0, $activity->totalElevationGain);
        $this->assertSame('Ride', $activity->type);
        $this->assertSame('MountainBikeRide', $activity->sportType);
        $this->assertSame('2018-05-02T12:15:09+00:00', $activity->startDate?->format('c'));
        $this->assertSame('2018-05-02T05:15:09+00:00', $activity->startDateLocal?->format('c'));
        $this->assertSame(5.54, $activity->averageSpeed);
        $this->assertSame(11.0, $activity->maxSpeed);
        $this->assertSame(140.3, $activity->averageHeartrate);
        $this->assertSame(178.0, $activity->maxHeartrate);
        $this->assertSame(890.4, $activity->kilojoules);
        $this->assertSame('b12345678987654321', $activity->gearId);
        $this->assertSame([37.83, -122.26], $activity->startLatlng);
        $this->assertSame([37.83, -122.26], $activity->endLatlng);
        $this->assertSame('abc123', $activity->mapPolyline);
        $this->assertFalse($activity->isManual);
        $this->assertTrue($activity->isFlagged);
        $this->assertTrue($activity->isTrainer);
        $this->assertSame(987654321234567891, $activity->uploadId);
        $this->assertSame('garmin_push_12345678987654321', $activity->externalId);
        $this->assertSame('Garmin Edge 1030', $activity->deviceName);
        $this->assertCount(25, get_object_vars($activity));
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

    #[Test]
    public function it_maps_the_provenance_fields_of_a_full_payload(): void
    {
        $activity = Activity::fromArray([
            'id' => 123,
            'name' => 'Morning Ride',
            'type' => 'Ride',
            'manual' => false,
            'flagged' => false,
            'trainer' => false,
            'upload_id' => 12345678901,
            'upload_id_str' => '12345678901',
            'external_id' => 'garmin_push_9876543210',
            'device_name' => 'Garmin Edge 830',
        ]);

        $this->assertFalse($activity->isManual);
        $this->assertFalse($activity->isFlagged);
        $this->assertFalse($activity->isTrainer);
        $this->assertSame(12345678901, $activity->uploadId);
        $this->assertSame('garmin_push_9876543210', $activity->externalId);
        $this->assertSame('Garmin Edge 830', $activity->deviceName);
    }

    #[Test]
    public function it_maps_a_manual_activity_without_upload(): void
    {
        $activity = Activity::fromArray([
            'id' => 7,
            'name' => 'Musculation',
            'type' => 'Workout',
            'manual' => true,
            'upload_id' => null,
            'external_id' => null,
        ]);

        $this->assertTrue($activity->isManual);
        $this->assertNull($activity->uploadId);
        $this->assertNull($activity->externalId);
    }

    #[Test]
    public function it_maps_a_flagged_activity(): void
    {
        $activity = Activity::fromArray([
            'id' => 8,
            'name' => 'Flagged',
            'type' => 'Run',
            'flagged' => true,
        ]);

        $this->assertTrue($activity->isFlagged);
    }

    #[Test]
    public function it_maps_a_trainer_activity(): void
    {
        $activity = Activity::fromArray([
            'id' => 9,
            'name' => 'Turbo',
            'type' => 'Ride',
            'trainer' => true,
        ]);

        $this->assertTrue($activity->isTrainer);
    }

    #[Test]
    public function it_falls_back_to_the_string_upload_id(): void
    {
        $activity = Activity::fromArray([
            'id' => 10,
            'name' => 'Uploaded',
            'type' => 'Run',
            'upload_id_str' => '98765432109876',
        ]);

        $this->assertSame(98765432109876, $activity->uploadId);
    }

    #[Test]
    public function it_prefers_the_numeric_upload_id_over_the_string_one(): void
    {
        $activity = Activity::fromArray([
            'id' => 11,
            'name' => 'Uploaded',
            'type' => 'Run',
            'upload_id' => 555,
            'upload_id_str' => '999',
        ]);

        $this->assertSame(555, $activity->uploadId);
    }

    #[Test]
    public function it_falls_back_to_the_string_upload_id_when_the_numeric_one_is_null(): void
    {
        $activity = Activity::fromArray([
            'id' => 12,
            'name' => 'Uploaded',
            'type' => 'Run',
            'upload_id' => null,
            'upload_id_str' => '98765432123456789',
        ]);

        $this->assertSame(98765432123456789, $activity->uploadId);
    }

    #[Test]
    public function it_maps_an_upload_id_overflowing_int64_to_null(): void
    {
        $activity = Activity::fromArray([
            'id' => 13,
            'name' => 'Uploaded',
            'type' => 'Run',
            'upload_id' => 987654321234567891234,
            'upload_id_str' => '987654321234567891234',
        ]);

        $this->assertNull($activity->uploadId);
    }

    #[Test]
    public function it_maps_an_empty_string_upload_id_to_null(): void
    {
        $activity = Activity::fromArray([
            'id' => 14,
            'name' => 'Uploaded',
            'type' => 'Run',
            'upload_id_str' => '',
        ]);

        $this->assertNull($activity->uploadId);
    }

    #[Test]
    public function it_falls_back_to_a_valid_string_when_the_numeric_upload_id_overflows(): void
    {
        $activity = Activity::fromArray([
            'id' => 15,
            'name' => 'Uploaded',
            'type' => 'Run',
            'upload_id' => 1.0e20,
            'upload_id_str' => '555',
        ]);

        $this->assertSame(555, $activity->uploadId);
    }

    #[Test]
    #[DataProvider('invalidUploadIds')]
    public function it_maps_an_invalid_upload_id_to_null(mixed $uploadId): void
    {
        $activity = Activity::fromArray([
            'id' => 16,
            'name' => 'Uploaded',
            'type' => 'Run',
            'upload_id' => $uploadId,
        ]);

        $this->assertNull($activity->uploadId);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function invalidUploadIds(): array
    {
        return [
            'zero' => [0],
            'negative integer' => [-5],
            'zero string' => ['0'],
            'negative string' => ['-5'],
            'non numeric string' => ['12abc'],
            'string above int64' => ['9223372036854775808'],
            'float' => [1.5],
            'boolean' => [true],
            'array' => [[1]],
        ];
    }

    #[Test]
    public function it_accepts_the_largest_int64_string_upload_id(): void
    {
        $activity = Activity::fromArray([
            'id' => 17,
            'name' => 'Uploaded',
            'type' => 'Run',
            'upload_id_str' => '9223372036854775807',
        ]);

        $this->assertSame(PHP_INT_MAX, $activity->uploadId);
    }

    #[Test]
    public function it_defaults_the_provenance_fields_to_null(): void
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

        $this->assertNull($activity->isManual);
        $this->assertNull($activity->isFlagged);
        $this->assertNull($activity->isTrainer);
        $this->assertNull($activity->uploadId);
        $this->assertNull($activity->externalId);
        $this->assertNull($activity->deviceName);
    }

    #[Test]
    public function it_keeps_the_positional_constructor_backward_compatible(): void
    {
        $activity = new Activity(
            123,
            'Morning Run',
            5000.5,
            1800,
            1900,
            42.0,
            'Run',
            'TrailRun',
            new DateTimeImmutable('2024-01-02T08:00:00Z'),
            new DateTimeImmutable('2024-01-02T09:00:00Z'),
            2.78,
            4.1,
            150.0,
            175.0,
            320.5,
            'g123',
            [48.85, 2.35],
            [48.86, 2.36],
            'abc123',
        );

        $this->assertSame(123, $activity->id);
        $this->assertSame('abc123', $activity->mapPolyline);
        $this->assertNull($activity->isManual);
        $this->assertNull($activity->isFlagged);
        $this->assertNull($activity->isTrainer);
        $this->assertNull($activity->uploadId);
        $this->assertNull($activity->externalId);
        $this->assertNull($activity->deviceName);
    }
}
