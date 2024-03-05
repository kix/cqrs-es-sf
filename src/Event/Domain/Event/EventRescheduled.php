<?php

declare(strict_types=1);

namespace CqrsEsExample\Event\Domain\Event;

use DateTimeImmutable;

final readonly class EventRescheduled
{
    public function __construct(
        public string $title,
        public string $location,
        public DateTimeImmutable $newStartDatetime,
        public DateTimeImmutable $newEndDatetime,
    ) {}
}