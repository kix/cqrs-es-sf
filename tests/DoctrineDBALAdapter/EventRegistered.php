<?php

declare(strict_types=1);

namespace Tests\DoctrineDBALAdapter;

use DateTimeImmutable;

final readonly class EventRegistered
{
    public function __construct(
        public DateTimeImmutable $startDate,
        public DateTimeImmutable $endDate,
        public string            $title,
        public string            $location,
    ) {}
}