<?php

namespace Foutraz\Strava\Tests\Feature\Actions;

use Foutraz\Strava\Auth\TokenManager;
use Foutraz\Strava\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class TokenManagerTest extends TestCase
{
    #[Test]
    public function it_returns_the_existing_token_when_not_expired(): void
    {
        $manager = $this->managerWithResponses([]);
        $tokenManager = new TokenManager($manager);

        $token = $tokenManager->ensureValidToken([
            'access_token' => 'still-valid',
            'refresh_token' => 'refresh',
            'expires_at' => time() + 3600,
        ]);

        $this->assertSame('still-valid', $token->accessToken);
        $this->assertSame('refresh', $token->refreshToken);
    }

    #[Test]
    public function it_refreshes_when_within_the_skew_window(): void
    {
        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, [
                'access_token' => 'fresh',
                'refresh_token' => 'rotated',
                'expires_at' => time() + 21600,
                'expires_in' => 21600,
                'token_type' => 'Bearer',
            ]),
        ]);
        $tokenManager = new TokenManager($manager);

        $token = $tokenManager->ensureValidToken([
            'access_token' => 'about-to-expire',
            'refresh_token' => 'refresh',
            'expires_at' => time() + 30,
        ]);

        $this->assertSame('fresh', $token->accessToken);
        $this->assertSame('rotated', $token->refreshToken);
    }

    #[Test]
    public function it_flags_a_token_within_sixty_seconds_as_expired(): void
    {
        $tokenManager = new TokenManager($this->managerWithResponses([]));

        $this->assertTrue($tokenManager->isExpired(time() + 30));
        $this->assertFalse($tokenManager->isExpired(time() + 120));
    }
}
