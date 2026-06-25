<?php

namespace Foutraz\Strava\Tests\Unit\Dto;

use Foutraz\Strava\Dto\ActivityStream;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ActivityStreamTest extends TestCase
{
    #[Test]
    public function it_maps_a_single_stream(): void
    {
        $stream = ActivityStream::fromArray([
            'type' => 'heartrate',
            'data' => [120, 130, 140],
            'series_type' => 'distance',
            'original_size' => 3,
            'resolution' => 'high',
        ]);

        $this->assertSame('heartrate', $stream->type);
        $this->assertSame([120, 130, 140], $stream->data);
        $this->assertSame('distance', $stream->seriesType);
        $this->assertSame(3, $stream->originalSize);
        $this->assertSame('high', $stream->resolution);
    }

    #[Test]
    public function it_defaults_nullable_fields_to_null(): void
    {
        $stream = ActivityStream::fromArray(['type' => 'time']);

        $this->assertSame([], $stream->data);
        $this->assertNull($stream->seriesType);
        $this->assertNull($stream->originalSize);
        $this->assertNull($stream->resolution);
    }

    #[Test]
    public function it_maps_a_collection(): void
    {
        $streams = ActivityStream::collectionFromArray([
            ['type' => 'time', 'data' => [0, 1]],
            ['type' => 'distance', 'data' => [0, 5]],
        ]);

        $this->assertCount(2, $streams);
        $this->assertSame('time', $streams[0]->type);
        $this->assertSame('distance', $streams[1]->type);
    }
}
