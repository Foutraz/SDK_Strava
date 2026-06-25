<?php

namespace Foutraz\Strava\Tests\Feature\Actions;

use Foutraz\Strava\Dto\TokenResponse;
use Foutraz\Strava\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ManagesAuthenticationTest extends TestCase
{
    #[Test]
    public function it_builds_the_authorize_url_with_default_scope(): void
    {
        $manager = $this->managerWithResponses([]);

        $url = $manager->auth()->authorizeUrl();

        $this->assertStringContainsString('client_id=client-id', $url);
        $this->assertStringContainsString('scope=read%2Cactivity%3Aread_all', $url);
    }

    #[Test]
    public function it_allows_overriding_the_scope(): void
    {
        $manager = $this->managerWithResponses([]);

        $url = $manager->auth()->authorizeUrl(['profile:read_all']);

        $this->assertStringContainsString('scope=profile%3Aread_all', $url);
    }

    #[Test]
    public function it_defaults_the_approval_prompt_to_auto(): void
    {
        $manager = $this->managerWithResponses([]);

        $url = $manager->auth()->authorizeUrl();

        $this->assertStringContainsString('approval_prompt=auto', $url);
    }

    #[Test]
    public function it_allows_forcing_the_approval_prompt(): void
    {
        $manager = $this->managerWithResponses([]);

        $url = $manager->auth()->authorizeUrl(approvalPrompt: 'force');

        $this->assertStringContainsString('approval_prompt=force', $url);
    }

    #[Test]
    public function it_exchanges_a_code_for_a_token_response(): void
    {
        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, [
                'access_token' => 'a',
                'refresh_token' => 'r',
                'expires_at' => 123,
                'expires_in' => 21600,
                'token_type' => 'Bearer',
                'athlete' => ['id' => 1],
            ]),
        ]);

        $token = $manager->auth()->exchangeToken('the-code');

        $this->assertInstanceOf(TokenResponse::class, $token);
        $this->assertSame('a', $token->accessToken);
        $this->assertSame(1, $token->athlete?->id);
        $this->assertSame('https://www.strava.com/oauth/token', $this->lastRequestUri());
    }

    #[Test]
    public function it_refreshes_a_token(): void
    {
        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, [
                'access_token' => 'a2',
                'refresh_token' => 'r2',
                'expires_at' => 456,
                'expires_in' => 21600,
                'token_type' => 'Bearer',
            ]),
        ]);

        $token = $manager->auth()->refreshToken('old-refresh');

        $this->assertSame('a2', $token->accessToken);
        $this->assertSame('r2', $token->refreshToken);
        $this->assertNull($token->athlete);
    }
}
