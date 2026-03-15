<?php

namespace Foutraz\Strava\Dto;

class Activity
{
    public function __construct(
        public int $id,
        public string $name,
        public float $distance,
        public int $movingTime,
        public string $type
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['name'],
            $data['distance'],
            $data['moving_time'],
            $data['type']
        );
    }
}