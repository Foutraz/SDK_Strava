<?php

namespace Foutraz\Strava\Dto;

class TotalsSummary
{
    public function __construct(
        public int $count,
        public float $distance,
        public int $movingTime,
        public float $elevationGain,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (int) ($data['count'] ?? 0),
            (float) ($data['distance'] ?? 0.0),
            (int) ($data['moving_time'] ?? 0),
            (float) ($data['elevation_gain'] ?? 0.0),
        );
    }
}
