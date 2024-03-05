<?php

declare(strict_types=1);

namespace CqrsEsExample\Event\Application\Command;

use CqrsEsExample\Common\Domain\AggregateRootRepository;
use CqrsEsExample\Event\Domain\EventCalendar;

final class ApproveEventHandler
{
    public function __construct(
        private readonly AggregateRootRepository $calendarRepository,
    ) {}

    public function __invoke(ApproveEventCommand $command): void
    {
        $aggregate = $this->calendarRepository->retrieve(EventCalendar::DEFAULT_CALENDAR_UUID);
        assert($aggregate instanceof EventCalendar);

        $aggregate->approveEvent(
            $command->startDatetime,
            $command->endDatetime,
            $command->title,
            $command->location,
        );

        $this->calendarRepository->persist($aggregate);
    }
}