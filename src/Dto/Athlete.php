<?php

namespace Foutraz\Strava\DTO;

class Athlete
{
    public function __construct(
        public int $id,
        public string $username,
        public string $firstname,
        public string $lastname
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['username'] ?? '',
            $data['firstname'] ?? '',
            $data['lastname'] ?? ''
        );
    }
}