<?php

declare(strict_types=1);

namespace CqrsEsExample\Event\Application\Command;

use DateTimeImmutable;

final class ApproveEventCommand
{
    public function __construct(
        public string $title,
        public DateTimeImmutable $startDatetime,
        public DateTimeImmutable $endDatetime,
        public string $location,
    ) {}
}