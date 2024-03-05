<?php

declare(strict_types=1);

namespace CqrsEsExample\Event\Application\Projection;

use CqrsEsExample\Event\Domain\Event\EventApproved;
use DateTimeInterface;
use Doctrine\DBAL\Connection;

final readonly class EventListProjector
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function __invoke(EventApproved $event): void
    {
        $stmt = $this->connection->prepare('insert into public.events values (:title, :start_time, :end_time, :location)');
        $stmt->executeQuery([
            $event->title,
            $event->startDate->format(DateTimeInterface::RFC3339_EXTENDED),
            $event->endDate->format(DateTimeInterface::RFC3339_EXTENDED),
            $event->location,
        ]);
    }
}