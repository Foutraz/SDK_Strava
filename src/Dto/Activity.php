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
        public ?bool $isManual = null,
        public ?bool $isFlagged = null,
        public ?bool $isTrainer = null,
        public ?int $uploadId = null,
        public ?string $externalId = null,
        public ?string $deviceName = null,
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
            isset($data['manual']) ? (bool) $data['manual'] : null,
            isset($data['flagged']) ? (bool) $data['flagged'] : null,
            isset($data['trainer']) ? (bool) $data['trainer'] : null,
            self::uploadIdFrom($data),
            isset($data['external_id']) ? (string) $data['external_id'] : null,
            isset($data['device_name']) ? (string) $data['device_name'] : null,
        );
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private static function uploadIdFrom(array $data): ?int
    {
        foreach ([$data['upload_id'] ?? null, $data['upload_id_str'] ?? null] as $candidate) {
            $uploadId = self::positiveInt($candidate);

            if ($uploadId !== null) {
                return $uploadId;
            }
        }

        return null;
    }

    private static function positiveInt(mixed $candidate): ?int
    {
        if (is_int($candidate)) {
            return $candidate > 0 ? $candidate : null;
        }

        if (! is_string($candidate) || ! ctype_digit($candidate)) {
            return null;
        }

        $integer = (int) $candidate;

        return $integer > 0 && (string) $integer === ltrim($candidate, '0') ? $integer : null;
    }
}
