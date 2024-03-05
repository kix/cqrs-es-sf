<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Domain;

use Exception;

final class AggregateException extends Exception
{
    public static function aggregateClassDoesNotExist(string $classname): self
    {
        return new self(sprintf(
            'Aggregate class %s does not exist',
            $classname
        ));
    }

    public static function aggregateDoesNotSubclassAggregateRoot(string $classname): self
    {
        return new self(sprintf(
            'Aggregate class %s does not extend AggregateRoot',
            $classname
        ));
    }

    public static function noEventsForAggregateRootId(string $classname, string $id): self
    {
        return new self(sprintf(
            'No events found for aggregate class %s with ID %s',
            $classname,
            $id
        ));
    }

    public static function cannotApplyEvent(
        object $event,
        string $message = null
    ): self
    {
        if ($message) {
            return new self($message);
        }

        return new self(sprintf(
            'No applicator method found for event class %s',
            $event::class,
        ));
    }

    public static function invariantViolated(Invariant $invariant): self
    {
        return new self(sprintf(
            'Invariant violated: %s',
            $invariant::class,
        ));
    }
}