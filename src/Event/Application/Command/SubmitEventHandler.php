<?php

declare(strict_types=1);

namespace CqrsEsExample\Event\Application\Command;

use CqrsEsExample\Common\Domain\AggregateRootRepository;
use CqrsEsExample\Event\Domain\EventCalendar;

final readonly class SubmitEventHandler
{
    public function __construct(
        /** @var AggregateRootRepository<EventCalendar> */
        private AggregateRootRepository $calendarRepository,
    ) {}

    public function __invoke(SubmitEventCommand $command): void
    {
        $calendar = $this->calendarRepository->retrieve(EventCalendar::DEFAULT_CALENDAR_UUID);
        assert($calendar instanceof EventCalendar);

        $calendar->registerEvent(
            $command->startDatetime,
            $command->endDatetime,
            $command->title,
            $command->location,
        );

        $this->calendarRepository->persist($calendar);
    }
}