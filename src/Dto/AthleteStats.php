<?php

namespace Foutraz\Strava\Dto;

class AthleteStats
{
    public function __construct(
        public TotalsSummary $recentRunTotals,
        public TotalsSummary $recentRideTotals,
        public TotalsSummary $recentSwimTotals,
        public TotalsSummary $ytdRunTotals,
        public TotalsSummary $ytdRideTotals,
        public TotalsSummary $ytdSwimTotals,
        public TotalsSummary $allRunTotals,
        public TotalsSummary $allRideTotals,
        public TotalsSummary $allSwimTotals,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            TotalsSummary::fromArray($data['recent_run_totals'] ?? []),
            TotalsSummary::fromArray($data['recent_ride_totals'] ?? []),
            TotalsSummary::fromArray($data['recent_swim_totals'] ?? []),
            TotalsSummary::fromArray($data['ytd_run_totals'] ?? []),
            TotalsSummary::fromArray($data['ytd_ride_totals'] ?? []),
            TotalsSummary::fromArray($data['ytd_swim_totals'] ?? []),
            TotalsSummary::fromArray($data['all_run_totals'] ?? []),
            TotalsSummary::fromArray($data['all_ride_totals'] ?? []),
            TotalsSummary::fromArray($data['all_swim_totals'] ?? []),
        );
    }
}
