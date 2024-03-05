<?php

declare(strict_types=1);

namespace CqrsEsExample\Event\Domain;

use CqrsEsExample\Common\Domain\AggregateException;
use CqrsEsExample\Event\Domain\Event\EventApproved;
use CqrsEsExample\Event\Domain\Event\EventCalendarRegistered;
use DateTimeImmutable;
use CqrsEsExample\Common\Domain\AggregateRoot;
use CqrsEsExample\Event\Domain\Event\EventRegistered;
use CqrsEsExample\Event\Domain\Invariants\NoTwoEventsAtSameLocationAndTime;
use Override;

final class EventCalendar extends AggregateRoot
{
    private bool $isRegistered = false;

    private array $events = [];

    public const string DEFAULT_CALENDAR_UUID = '024a9577-de3e-49c5-96ef-fae123b6e577';

    public function __construct(string $id)
    {
        parent::__construct($id);
    }

    /**
     * @TODO An aggregate always needs at least one event to exist. How else to resolve that than with
     * synthetic events?
     */
    public function register(): void
    {
        if ($this->isRegistered) {
            throw new AggregateException('Cannot register a calendar twice');
        }

        $this->recordThat(new EventCalendarRegistered());
    }

    #[Override]
    protected function invariants(): array
    {
        return [
            NoTwoEventsAtSameLocationAndTime::class,
        ];
    }

    protected function applyEventCalendarRegistered(EventCalendarRegistered $event): void
    {
        $this->isRegistered = true;
    }

    public function registerEvent(
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate,
        string $title,
        string $location,
    ): void
    {
        $this->recordThat(new EventRegistered(
            $startDate,
            $endDate,
            $title,
            $location
        ));
    }

    protected function applyEventRegistered(EventRegistered $event): void
    {
        $this->events [] = [
            'title' => $event->title,
            'location' => $event->location,
            'startDateTime' => $event->startDate,
            'endDateTime' => $event->endDate,
        ];
    }

    public function approveEvent(
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate,
        string $title,
        string $location,
    ): void {
        $this->recordThat(new EventApproved(
            $startDate,
            $endDate,
            $title,
            $location,
        ));
    }

    protected function applyEventApproved(EventApproved $event): void
    {
        //
    }
}