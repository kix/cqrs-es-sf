<?php

declare(strict_types=1);

namespace CqrsEsExample\Event\Application\Query;

use DateTimeImmutable;

final readonly class ListEventsQuery
{
    public function __construct(
        public ?DateTimeImmutable $fromDate = null,
        public ?DateTimeImmutable $endDate = null,
        public ?string            $location = null,
        public ?string            $title = null,
    ) {}
}