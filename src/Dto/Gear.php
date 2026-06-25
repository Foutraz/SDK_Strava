<?php

namespace Foutraz\Strava\Dto;

class Gear
{
    public function __construct(
        public string $id,
        public string $name,
        public float $distance,
        public ?string $brandName,
        public ?string $modelName,
        public bool $primary,
        public int $resourceState,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (string) $data['id'],
            (string) ($data['name'] ?? ''),
            (float) ($data['distance'] ?? 0.0),
            $data['brand_name'] ?? null,
            $data['model_name'] ?? null,
            (bool) ($data['primary'] ?? false),
            (int) ($data['resource_state'] ?? 0),
        );
    }
}
