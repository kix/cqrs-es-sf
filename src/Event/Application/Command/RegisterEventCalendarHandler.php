<?php

declare(strict_types=1);

namespace CqrsEsExample\Event\Application\Command;

use CqrsEsExample\Common\Domain\AggregateException;
use CqrsEsExample\Common\Domain\AggregateRootRepository;
use CqrsEsExample\Event\Domain\EventCalendar;

final class RegisterEventCalendarHandler
{
    public function __construct(
        private AggregateRootRepository $calendarRepository,
    ) {}

    public function __invoke(RegisterEventCalendarCommand $command): void
    {
        try {
            $this->calendarRepository->retrieve(EventCalendar::DEFAULT_CALENDAR_UUID);
        } catch (AggregateException $e) {
            $calendar = new EventCalendar(EventCalendar::DEFAULT_CALENDAR_UUID);
            $calendar->register();
            $this->calendarRepository->persist($calendar);
        }
    }
}