<?php

namespace Foutraz\Strava\Tests\Unit\Concerns;

use Foutraz\Strava\Dto\Activity;
use Foutraz\Strava\Exceptions\ActionFailed;
use Foutraz\Strava\Exceptions\InvalidData;
use Foutraz\Strava\Exceptions\ResourceNotFound;
use Foutraz\Strava\Exceptions\TooManyRequestsException;
use Foutraz\Strava\Exceptions\Unauthorized;
use Foutraz\Strava\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MakesHttpRequestsTest extends TestCase
{
    #[Test]
    public function it_maps_401_to_unauthorized(): void
    {
        $manager = $this->managerWithResponses([$this->jsonResponse(401, ['message' => 'nope'])]);

        $this->expectException(Unauthorized::class);

        $manager->athletes()->me();
    }

    #[Test]
    public function it_maps_404_to_resource_not_found(): void
    {
        $manager = $this->managerWithResponses([$this->jsonResponse(404, [])]);

        $this->expectException(ResourceNotFound::class);

        $manager->activities()->find(1);
    }

    #[Test]
    public function it_maps_422_to_invalid_data(): void
    {
        $manager = $this->managerWithResponses([$this->jsonResponse(422, ['errors' => ['field']])]);

        $this->expectException(InvalidData::class);

        $manager->athletes()->me();
    }

    #[Test]
    public function it_maps_400_to_action_failed(): void
    {
        $manager = $this->managerWithResponses([$this->jsonResponse(400, ['message' => 'bad'])]);

        $this->expectException(ActionFailed::class);

        $manager->athletes()->me();
    }

    #[Test]
    public function it_maps_429_to_too_many_requests(): void
    {
        $manager = $this->managerWithResponses([$this->jsonResponse(429, ['message' => 'rate limited'])]);

        $this->expectException(TooManyRequestsException::class);

        $manager->athletes()->me();
    }

    #[Test]
    public function it_iterates_pages_until_a_short_page(): void
    {
        $firstPage = array_map(static fn (int $id): array => ['id' => $id, 'name' => "Run $id", 'type' => 'Run'], range(1, 2));
        $secondPage = [['id' => 3, 'name' => 'Run 3', 'type' => 'Run']];

        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, $firstPage),
            $this->jsonResponse(200, $secondPage),
        ]);

        $activities = iterator_to_array($manager->activities()->iterate(2));

        $this->assertCount(3, $activities);
        $this->assertContainsOnlyInstancesOf(Activity::class, $activities);
        $this->assertSame([1, 2, 3], array_map(static fn (Activity $a): int => $a->id, $activities));
    }

    #[Test]
    public function it_stops_iterating_on_an_empty_page(): void
    {
        $manager = $this->managerWithResponses([$this->jsonResponse(200, [])]);

        $activities = iterator_to_array($manager->activities()->iterate(200));

        $this->assertCount(0, $activities);
    }
}
