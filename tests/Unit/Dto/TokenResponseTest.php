<?php

namespace Foutraz\Strava\Tests\Unit\Dto;

use Foutraz\Strava\Dto\TokenResponse;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class TokenResponseTest extends TestCase
{
    #[Test]
    public function it_maps_a_full_payload_with_athlete(): void
    {
        $token = TokenResponse::fromArray([
            'access_token' => 'access',
            'refresh_token' => 'refresh',
            'expires_at' => 1700000000,
            'expires_in' => 21600,
            'token_type' => 'Bearer',
            'athlete' => ['id' => 7, 'username' => 'me'],
        ]);

        $this->assertSame('access', $token->accessToken);
        $this->assertSame('refresh', $token->refreshToken);
        $this->assertSame(1700000000, $token->expiresAt);
        $this->assertSame(21600, $token->expiresIn);
        $this->assertSame('Bearer', $token->tokenType);
        $this->assertSame(7, $token->athlete?->id);
    }

    #[Test]
    public function it_defaults_athlete_to_null(): void
    {
        $token = TokenResponse::fromArray([
            'access_token' => 'access',
            'refresh_token' => 'refresh',
            'expires_at' => 1,
            'expires_in' => 2,
        ]);

        $this->assertNull($token->athlete);
        $this->assertSame('Bearer', $token->tokenType);
    }
}
