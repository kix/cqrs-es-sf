<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Infrastructure;

use DateTimeInterface;
use LogicException;

final readonly class DateTimeRange
{
    public function __construct(
        private DateTimeInterface $startDate,
        private DateTimeInterface $endDate,
    ) {
        if ($this->endDate < $this->startDate) {
            throw new LogicException('End date cannot be before start date');
        }

        if ($this->startDate == $this->endDate) {
            throw new LogicException('Empty date range passed');
        }
    }

    public function overlaps(self $other): bool
    {
        return $this->startDate < $other->endDate
            && $this->endDate > $other->startDate;
    }

    public function equals(self $other): bool
    {
        return $other->startDate === $this->startDate
            && $other->endDate === $this->endDate;
    }

    public function __toString(): string
    {
        return $this->startDate->format(DATE_ATOM).'->'.$this->endDate->format(DATE_ATOM);
    }
}