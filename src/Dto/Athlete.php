<?php

namespace Foutraz\Strava\Dto;

use DateTimeImmutable;

class Athlete
{
    public function __construct(
        public int $id,
        public string $username,
        public string $firstname,
        public string $lastname,
        public ?string $profileImageUrl,
        public ?string $city,
        public ?string $country,
        public ?string $sex,
        public ?float $weight,
        public ?DateTimeImmutable $createdAt,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (int) $data['id'],
            (string) ($data['username'] ?? ''),
            (string) ($data['firstname'] ?? ''),
            (string) ($data['lastname'] ?? ''),
            $data['profile'] ?? null,
            $data['city'] ?? null,
            $data['country'] ?? null,
            $data['sex'] ?? null,
            isset($data['weight']) ? (float) $data['weight'] : null,
            isset($data['created_at']) ? new DateTimeImmutable($data['created_at']) : null,
        );
    }
}
