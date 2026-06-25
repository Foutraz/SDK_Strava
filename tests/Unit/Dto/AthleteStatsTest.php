<?php

namespace Foutraz\Strava\Tests\Unit\Dto;

use Foutraz\Strava\Dto\AthleteStats;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class AthleteStatsTest extends TestCase
{
    #[Test]
    public function it_maps_nested_totals(): void
    {
        $stats = AthleteStats::fromArray([
            'recent_run_totals' => ['count' => 5, 'distance' => 50000.0, 'moving_time' => 18000, 'elevation_gain' => 200.0],
            'ytd_ride_totals' => ['count' => 30, 'distance' => 900000.0, 'moving_time' => 360000, 'elevation_gain' => 8000.0],
            'all_swim_totals' => ['count' => 10, 'distance' => 20000.0, 'moving_time' => 24000, 'elevation_gain' => 0.0],
        ]);

        $this->assertSame(5, $stats->recentRunTotals->count);
        $this->assertSame(50000.0, $stats->recentRunTotals->distance);
        $this->assertSame(30, $stats->ytdRideTotals->count);
        $this->assertSame(10, $stats->allSwimTotals->count);
    }

    #[Test]
    public function it_defaults_missing_totals_to_zero(): void
    {
        $stats = AthleteStats::fromArray([]);

        $this->assertSame(0, $stats->allRunTotals->count);
        $this->assertSame(0.0, $stats->allRunTotals->distance);
        $this->assertSame(0, $stats->allRunTotals->movingTime);
        $this->assertSame(0.0, $stats->allRunTotals->elevationGain);
    }
}
