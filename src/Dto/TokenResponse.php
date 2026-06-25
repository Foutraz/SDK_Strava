<?php

namespace Foutraz\Strava\Dto;

class TokenResponse
{
    public function __construct(
        public string $accessToken,
        public string $refreshToken,
        public int $expiresAt,
        public int $expiresIn,
        public string $tokenType,
        public ?Athlete $athlete,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (string) ($data['access_token'] ?? ''),
            (string) ($data['refresh_token'] ?? ''),
            (int) ($data['expires_at'] ?? 0),
            (int) ($data['expires_in'] ?? 0),
            (string) ($data['token_type'] ?? 'Bearer'),
            isset($data['athlete']) && is_array($data['athlete']) ? Athlete::fromArray($data['athlete']) : null,
        );
    }
}
