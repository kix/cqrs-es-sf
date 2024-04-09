<?php

declare(strict_types=1);

namespace Event\Domain\Invariant;

use CqrsEsExample\Common\Domain\AggregateException;
use CqrsEsExample\Event\Domain\Event\EventApproved;
use CqrsEsExample\Event\Domain\Invariants\NoTwoEventsAtSameLocationAndTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class NoTwoEventsAtSameLocationAndTimeTest extends TestCase
{
    /**
     * @test
     */
    public function it_allows_events_without_overlap(): void
    {
        $this->expectNotToPerformAssertions();

        NoTwoEventsAtSameLocationAndTime::reconstituteFromEvents(
            [
                new EventApproved(
                    new DateTimeImmutable('2020-01-01 20:00:00'),
                    new DateTimeImmutable('2020-01-01 22:00:00'),
                    'Event name',
                    'Location name'
                ),
                new EventApproved(
                    new DateTimeImmutable('2020-01-02 00:00:00'),
                    new DateTimeImmutable('2020-01-02 02:00:00'),
                    'Event name',
                    'Location name'
                ),
            ]
        );
    }

    /**
     * @test
     */
    public function it_disallows_two_events_at_same_location_and_time(): void
    {
        $this->expectException(AggregateException::class);

        NoTwoEventsAtSameLocationAndTime::reconstituteFromEvents(
            [
                new EventApproved(
                    new DateTimeImmutable('2020-01-01 20:00:00'),
                    new DateTimeImmutable('2020-01-02 00:00:00'),
                    'Event name',
                    'Location name'
                ),
                new EventApproved(
                    new DateTimeImmutable('2020-01-01 22:00:00'),
                    new DateTimeImmutable('2020-01-02 02:00:00'),
                    'Event name',
                    'Location name'
                ),
            ]
        );
    }
}