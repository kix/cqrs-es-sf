<?php

declare(strict_types=1);

namespace Tests\Event\Domain\Event;

use DateTimeImmutable;
use CqrsEsExample\Common\Infrastructure\EventStorage\ObjectSerializer;
use CqrsEsExample\Event\Domain\EventCalendar;
use PHPUnit\Framework\TestCase;

final class EventCalendarTest extends TestCase
{
    /**
     * @test
     */
    public function it_registers_events(): void
    {
        $aggregate = new EventCalendar();

        $aggregate->registerEvent(
            new DateTimeImmutable('2023-01-01 14:00:00'),
            new DateTimeImmutable('2023-01-01 18:00:00'),
            'Rammstein concert',
            'Park Ušće',
        );

        $aggregate->registerEvent(
            new DateTimeImmutable('2023-01-01 16:00:00'),
            new DateTimeImmutable('2023-01-01 20:00:00'),
            'Kids\' concert',
            'Park Ušće',
        );
    }

    /**
     * @test
     */
    public function events_are_serializable(): void
    {
        $aggregate = new EventCalendar();

        $aggregate->registerEvent(
            new DateTimeImmutable('2023-01-01 14:00:00'),
            new DateTimeImmutable('2023-01-01 18:00:00'),
            'Rammstein concert',
            'Park Ušće',
        );

        $events = $aggregate->releaseEvents();

        $serializer = new ObjectSerializer();
    }
}