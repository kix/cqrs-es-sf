<?php

declare(strict_types=1);

namespace Common;

use CqrsEsExample\Common\Infrastructure\DateTimeRange;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use LogicException;

final class DateTimeRangeTest extends TestCase
{
    /**
     * @test
     */
    public function it_instantiates(): void
    {
        $this->expectNotToPerformAssertions();

        new DateTimeRange(
            new DateTimeImmutable('2022-01-01 22:00:00'),
            new DateTimeImmutable('2022-01-01 23:00:00'),
        );
    }

    /**
     * @test
     */
    public function it_disallows_empty_ranges(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Empty date range passed');

        new DateTimeRange(
            new DateTimeImmutable('2022-01-01 22:00:00'),
            new DateTimeImmutable('2022-01-01 22:00:00'),
        );
    }

    /**
     * @test
     */
    public function it_disallows_flipped_ranges(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('End date cannot be before start date');

        new DateTimeRange(
            new DateTimeImmutable('2022-01-01 22:00:00'),
            new DateTimeImmutable('2022-01-01 20:00:00'),
        );
    }

    /**
     * @test
     * @dataProvider provideRanges
     */
    public function it_checks_for_overlaps(bool $shouldOverlap, DateTimeRange $firstRange, DateTimeRange $secondRange): void
    {
        static::assertEquals(
            $shouldOverlap,
            $firstRange->overlaps($secondRange),
            $shouldOverlap ?
                'Range ' . $firstRange . ' should report as overlapping with ' . $secondRange . ' but it did not'
                : 'Range ' . $firstRange . ' should not report as overlapping with ' . $secondRange,
        );
    }

    public static function provideRanges(): array
    {
        return [
            [
                false,
                new DateTimeRange(
                    new DateTimeImmutable('2020-01-01 12:00:00'),
                    new DateTimeImmutable('2020-01-01 14:00:00'),
                ),
                new DateTimeRange(
                    new DateTimeImmutable('2020-01-01 15:00:00'),
                    new DateTimeImmutable('2020-01-01 17:00:00'),
                ),
            ],
            [
                true,
                new DateTimeRange(
                    new DateTimeImmutable('2020-01-01 12:00:00'),
                    new DateTimeImmutable('2020-01-01 16:00:00'),
                ),
                new DateTimeRange(
                    new DateTimeImmutable('2020-01-01 14:00:00'),
                    new DateTimeImmutable('2020-01-01 18:00:00'),
                ),
            ]
        ];
    }
}