<?php

namespace Foutraz\Strava\Dto;

use DateTimeImmutable;

class Activity
{
    /**
     * @param  array<int, float>|null  $startLatlng
     * @param  array<int, float>|null  $endLatlng
     */
    public function __construct(
        public int $id,
        public string $name,
        public float $distance,
        public int $movingTime,
        public int $elapsedTime,
        public float $totalElevationGain,
        public string $type,
        public string $sportType,
        public ?DateTimeImmutable $startDate,
        public ?DateTimeImmutable $startDateLocal,
        public ?float $averageSpeed,
        public ?float $maxSpeed,
        public ?float $averageHeartrate,
        public ?float $maxHeartrate,
        public ?float $kilojoules,
        public ?string $gearId,
        public ?array $startLatlng,
        public ?array $endLatlng,
        public ?string $mapPolyline,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (int) $data['id'],
            (string) ($data['name'] ?? ''),
            (float) ($data['distance'] ?? 0.0),
            (int) ($data['moving_time'] ?? 0),
            (int) ($data['elapsed_time'] ?? 0),
            (float) ($data['total_elevation_gain'] ?? 0.0),
            (string) ($data['type'] ?? ''),
            (string) ($data['sport_type'] ?? $data['type'] ?? ''),
            isset($data['start_date']) ? new DateTimeImmutable($data['start_date']) : null,
            isset($data['start_date_local']) ? new DateTimeImmutable($data['start_date_local']) : null,
            isset($data['average_speed']) ? (float) $data['average_speed'] : null,
            isset($data['max_speed']) ? (float) $data['max_speed'] : null,
            isset($data['average_heartrate']) ? (float) $data['average_heartrate'] : null,
            isset($data['max_heartrate']) ? (float) $data['max_heartrate'] : null,
            isset($data['kilojoules']) ? (float) $data['kilojoules'] : null,
            $data['gear_id'] ?? null,
            $data['start_latlng'] ?? null,
            $data['end_latlng'] ?? null,
            $data['map']['summary_polyline'] ?? null,
        );
    }
}
