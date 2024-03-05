<?php

declare(strict_types=1);

namespace CqrsEsExample\Event\Domain\Invariants;

use CqrsEsExample\Event\Domain\Event\EventApproved;
use DateInterval;
use DatePeriod;
use CqrsEsExample\Common\Domain\AggregateException;
use CqrsEsExample\Common\Domain\Invariant;
    use CqrsEsExample\Event\Domain\Event\EventRescheduled;

final class NoTwoEventsAtSameLocationAndTime extends Invariant
{
    /**
     * @var array<string,array<DatePeriod>>
     */
    private array $locationPeriods = [];

    protected function applyEventApproved(EventApproved $event): void
    {
        $this->addPeriod(
            $event->location,
            new DatePeriod(
                $event->startDate,
                new DateInterval('P30D'),
                $event->endDate,
            )
        );
    }

    protected function applyEventRescheduleApproved(EventRescheduled $event): void
    {
        $this->clearPeriod(
            $event->location,
            new DatePeriod(
                $event->newStartDatetime,
                new DateInterval('P30D'),
                $event->newEndDatetime
            )
        );

        $this->addPeriod(
            $event->location,
            new DatePeriod(
                $event->newStartDatetime,
                new DateInterval('P30D'),
                $event->newEndDatetime,
            )
        );
    }

    /**
     * @throws AggregateException
     */
    private function checkOverlap(string $location, DatePeriod $newPeriod): void
    {
        foreach ($this->locationPeriods[$location] as $period) {
            if (self::periodsOverlap($period, $newPeriod)) {
                throw AggregateException::invariantViolated(
                    $this
                );
            }
        }
    }

    /**
     * @throws AggregateException
     */
    private function addPeriod(string $location, DatePeriod $newPeriod): void
    {
        if (!array_key_exists($location, $this->locationPeriods)) {
            $this->locationPeriods[$location] = [];
        }

        $this->checkOverlap($location, $newPeriod);
        $this->locationPeriods[$location][]= $newPeriod;
    }

    private function clearPeriod(string $location, DatePeriod $period): void
    {
        $newPeriods = array_filter(
            $this->locationPeriods[$location],
            static fn (DatePeriod $existingPeriod) => $existingPeriod !== $period
        );

        $this->locationPeriods[$location] = $newPeriods;
    }

    private static function periodsOverlap(DatePeriod $datePeriod1, DatePeriod $datePeriod2): bool
    {
        return ($datePeriod1->getStartDate() <= $datePeriod2->getEndDate()
            && $datePeriod2->getEndDate() >= $datePeriod2->getStartDate());
    }
}