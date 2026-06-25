<?php

namespace Foutraz\Strava\Tests\Feature\Actions;

use Foutraz\Strava\Dto\Athlete;
use Foutraz\Strava\Dto\AthleteStats;
use Foutraz\Strava\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ManagesAthletesTest extends TestCase
{
    #[Test]
    public function it_returns_the_current_athlete(): void
    {
        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, ['id' => 7, 'username' => 'me', 'firstname' => 'Jane', 'lastname' => 'Doe']),
        ]);

        $athlete = $manager->athletes()->me();

        $this->assertInstanceOf(Athlete::class, $athlete);
        $this->assertSame(7, $athlete->id);
        $this->assertStringContainsString('/athlete', $this->lastRequestUri());
    }

    #[Test]
    public function it_returns_athlete_stats(): void
    {
        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, [
                'all_run_totals' => ['count' => 3, 'distance' => 30000.0, 'moving_time' => 9000, 'elevation_gain' => 100.0],
            ]),
        ]);

        $stats = $manager->athletes()->stats(7);

        $this->assertInstanceOf(AthleteStats::class, $stats);
        $this->assertSame(3, $stats->allRunTotals->count);
        $this->assertStringContainsString('athletes/7/stats', $this->lastRequestUri());
    }
}
