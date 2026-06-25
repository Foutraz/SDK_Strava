<?php

namespace Foutraz\Strava\Tests\Feature\Actions;

use Foutraz\Strava\Dto\Activity;
use Foutraz\Strava\Dto\ActivityStream;
use Foutraz\Strava\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ManagesActivitiesTest extends TestCase
{
    #[Test]
    public function it_lists_activities_as_dtos(): void
    {
        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, [
                ['id' => 1, 'name' => 'A', 'type' => 'Run'],
                ['id' => 2, 'name' => 'B', 'type' => 'Ride'],
            ]),
        ]);

        $activities = $manager->activities()->list();

        $this->assertCount(2, $activities);
        $this->assertContainsOnlyInstancesOf(Activity::class, $activities);
        $this->assertSame('A', $activities[0]->name);
    }

    #[Test]
    public function it_passes_query_filters_when_listing(): void
    {
        $manager = $this->managerWithResponses([$this->jsonResponse(200, [])]);

        $manager->activities()->list(['per_page' => 5, 'page' => 2]);

        $this->assertStringContainsString('per_page=5', $this->lastRequestUri());
        $this->assertStringContainsString('page=2', $this->lastRequestUri());
    }

    #[Test]
    public function it_finds_a_single_activity(): void
    {
        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, ['id' => 42, 'name' => 'Solo', 'type' => 'Run']),
        ]);

        $activity = $manager->activities()->find(42);

        $this->assertSame(42, $activity->id);
        $this->assertStringContainsString('activities/42', $this->lastRequestUri());
    }

    #[Test]
    public function it_fetches_activity_streams(): void
    {
        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, [
                ['type' => 'time', 'data' => [0, 1, 2]],
                ['type' => 'heartrate', 'data' => [120, 130, 140]],
            ]),
        ]);

        $streams = $manager->activities()->streams(42);

        $this->assertCount(2, $streams);
        $this->assertContainsOnlyInstancesOf(ActivityStream::class, $streams);
        $this->assertSame('heartrate', $streams[1]->type);
    }

    #[Test]
    public function it_iterates_across_pages_with_after_filter(): void
    {
        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, [['id' => 1, 'name' => 'A', 'type' => 'Run']]),
            $this->jsonResponse(200, []),
        ]);

        $activities = iterator_to_array($manager->activities()->iterate(1, 1600000000));

        $this->assertCount(1, $activities);
        $this->assertStringContainsString('after=1600000000', $this->lastRequestUri());
    }
}
