<?php

declare(strict_types=1);

namespace CqrsEsExample\Event\Application\Command;

use DateTimeImmutable;
use CqrsEsExample\Common\Infrastructure\Transport\MessageTypeEnum;

/**
 * A user wants to submit an event he knows about, and for that he submits it to our service for further validation.
 */
final readonly class SubmitEventCommand
{
    public function __construct(
        public string $title,
        public DateTimeImmutable $startDatetime,
        public DateTimeImmutable $endDatetime,
        public string $location,
    ) {}
}