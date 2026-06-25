<?php

namespace Foutraz\Strava\Tests\Unit\Dto;

use Foutraz\Strava\Dto\Gear;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class GearTest extends TestCase
{
    #[Test]
    public function it_maps_a_full_payload(): void
    {
        $gear = Gear::fromArray([
            'id' => 'b123',
            'name' => 'Trek',
            'distance' => 12345.6,
            'brand_name' => 'Trek',
            'model_name' => 'Emonda',
            'primary' => true,
            'resource_state' => 3,
        ]);

        $this->assertSame('b123', $gear->id);
        $this->assertSame('Trek', $gear->name);
        $this->assertSame(12345.6, $gear->distance);
        $this->assertSame('Trek', $gear->brandName);
        $this->assertSame('Emonda', $gear->modelName);
        $this->assertTrue($gear->primary);
        $this->assertSame(3, $gear->resourceState);
    }

    #[Test]
    public function it_defaults_nullable_fields_to_null(): void
    {
        $gear = Gear::fromArray(['id' => 'g1', 'name' => 'Shoes']);

        $this->assertSame(0.0, $gear->distance);
        $this->assertNull($gear->brandName);
        $this->assertNull($gear->modelName);
        $this->assertFalse($gear->primary);
        $this->assertSame(0, $gear->resourceState);
    }
}
