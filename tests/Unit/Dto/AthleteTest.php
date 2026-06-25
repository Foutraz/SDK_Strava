<?php

namespace Foutraz\Strava\Tests\Unit\Dto;

use Foutraz\Strava\Dto\Athlete;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class AthleteTest extends TestCase
{
    #[Test]
    public function it_maps_a_full_payload(): void
    {
        $athlete = Athlete::fromArray([
            'id' => 99,
            'username' => 'runner',
            'firstname' => 'Jane',
            'lastname' => 'Doe',
            'profile' => 'https://example.test/avatar.png',
            'city' => 'Paris',
            'country' => 'France',
            'sex' => 'F',
            'weight' => 62.5,
            'created_at' => '2020-05-01T10:00:00Z',
        ]);

        $this->assertSame(99, $athlete->id);
        $this->assertSame('runner', $athlete->username);
        $this->assertSame('Jane', $athlete->firstname);
        $this->assertSame('Doe', $athlete->lastname);
        $this->assertSame('https://example.test/avatar.png', $athlete->profileImageUrl);
        $this->assertSame('Paris', $athlete->city);
        $this->assertSame('France', $athlete->country);
        $this->assertSame('F', $athlete->sex);
        $this->assertSame(62.5, $athlete->weight);
        $this->assertSame('2020-05-01T10:00:00+00:00', $athlete->createdAt?->format('c'));
    }

    #[Test]
    public function it_defaults_nullable_fields_to_null(): void
    {
        $athlete = Athlete::fromArray(['id' => 1]);

        $this->assertSame('', $athlete->username);
        $this->assertNull($athlete->profileImageUrl);
        $this->assertNull($athlete->city);
        $this->assertNull($athlete->weight);
        $this->assertNull($athlete->createdAt);
    }
}
