<?php

namespace Foutraz\Strava\Dto;

class ActivityStream
{
    /**
     * @param  array<int, mixed>  $data
     */
    public function __construct(
        public string $type,
        public array $data,
        public ?string $seriesType,
        public ?int $originalSize,
        public ?string $resolution,
    ) {}

    /**
     * @param  array<string, mixed>  $stream
     */
    public static function fromArray(array $stream): self
    {
        return new self(
            (string) ($stream['type'] ?? ''),
            $stream['data'] ?? [],
            $stream['series_type'] ?? null,
            isset($stream['original_size']) ? (int) $stream['original_size'] : null,
            $stream['resolution'] ?? null,
        );
    }

    /**
     * @param  array<int, array<string, mixed>>  $streams
     * @return array<int, ActivityStream>
     */
    public static function collectionFromArray(array $streams): array
    {
        return array_map(static fn (array $stream): self => self::fromArray($stream), $streams);
    }
}
