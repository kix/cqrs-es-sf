<?php

declare(strict_types=1);

namespace CqrsEsExample\Event\Domain\Invariants;

use CqrsEsExample\Common\Infrastructure\DateTimeRange;
use CqrsEsExample\Event\Domain\Event\EventApproved;
use CqrsEsExample\Common\Domain\AggregateException;
use CqrsEsExample\Common\Domain\Invariant;
use Stringable;

/**
 * @TODO Applicator methods shouldn't be public
 */
final class NoTwoEventsAtSameLocationAndTime extends Invariant implements Stringable
{
    /**
     * Here we have a conclusion that event name and location name pairs are unique
     *
     * @var array<string,array<DateTimeRange>>
     */
    private array $eventDateRanges = [];

    /**
     * @throws AggregateException
     */
    public function applyEventApproved(EventApproved $event): void
    {
        $this->addRange(
            $event->title . '-'.$event->location,
            new DateTimeRange(
                $event->startDate,
                $event->endDate
            )
        );
    }

    /**
     * @throws AggregateException
     */
    private function checkOverlap(string $eventId, DateTimeRange $newRange): void
    {
        $self = $this;

        array_map(static function (DateTimeRange $range) use ($newRange, $self): void {
            if ($range->overlaps($newRange)) {
                throw AggregateException::invariantViolated($self);
            }
        }, $this->eventDateRanges[$eventId]);
    }

    /**
     * @throws AggregateException
     */
    private function addRange(string $eventId, DateTimeRange $range): void
    {
        if (!array_key_exists($eventId, $this->eventDateRanges)) {
            $this->eventDateRanges[$eventId] = [];
        }

        $this->checkOverlap($eventId, $range);
        $this->eventDateRanges[$eventId][]= $range;
    }

    public function __toString()
    {
        return 'Two events cannot happen at the same time and at the same location';
    }
}