<?php

declare(strict_types=1);

namespace CqrsEsExample\Event\Domain\Event;

use DateTimeImmutable;

final readonly class EventApproved
{
    public function __construct(
        public DateTimeImmutable $startDate,
        public DateTimeImmutable $endDate,
        public string            $title,
        public string            $location,
    ) { }
}