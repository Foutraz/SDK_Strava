<?php

namespace Foutraz\Strava\Tests\Feature\Actions;

use Foutraz\Strava\Dto\Gear;
use Foutraz\Strava\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ManagesGearsTest extends TestCase
{
    #[Test]
    public function it_finds_a_gear(): void
    {
        $manager = $this->managerWithResponses([
            $this->jsonResponse(200, [
                'id' => 'b123',
                'name' => 'Road Bike',
                'distance' => 50000.0,
                'primary' => true,
                'resource_state' => 3,
            ]),
        ]);

        $gear = $manager->gears()->find('b123');

        $this->assertInstanceOf(Gear::class, $gear);
        $this->assertSame('b123', $gear->id);
        $this->assertSame('Road Bike', $gear->name);
        $this->assertStringContainsString('gear/b123', $this->lastRequestUri());
    }
}
