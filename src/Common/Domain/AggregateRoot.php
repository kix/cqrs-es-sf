<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Domain;

use Generator;
use ReflectionClass;
use ReflectionException;

/**
 * An aggregate root is the main source of truth in the domain layer. It is composed (or reconstituted)
 * from the events that have occurred inside of it.
 */
abstract class AggregateRoot
{
    /**
     * @var array<object>
     */
    private array $domainEvents = [];

    public function id(): string
    {
        return $this->id;
    }

    public function __construct(
        private readonly string $id,
    ) {}

    /**
     * @param Generator<object> $events
     * @throws AggregateException
     * @throws ReflectionException
     */
    public static function reconstituteFromEvents(string $id, Generator $events): self
    {
        $instance = new static($id);

        foreach ($events as $event) {
            $eventClass = (new ReflectionClass($event))->getShortName();
            $applicatorMethodName = 'apply'.$eventClass;

            if (!method_exists($instance, $applicatorMethodName)) {
                throw AggregateException::cannotApplyEvent($event);
            }

            $instance->$applicatorMethodName($event);
        }

        return $instance;
    }

    protected function recordThat(object $event): void
    {
        $this->domainEvents []= $event;

        foreach ($this->invariants() as $invariant) {
            $invariant::reconstituteFromEvents($this->domainEvents);
        }
    }

    /**
     * @return array<class-string<Invariant>>
     */
    protected function invariants(): array
    {
        return [];
    }

    /**
     * @return array<object>
     */
    final public function releaseEvents(): array
    {
        foreach ($this->invariants() as $invariant) {
            $invariant::reconstituteFromEvents($this->domainEvents);
        }

        $events = $this->domainEvents;
        $this->domainEvents = [];

        return $events;
    }
}