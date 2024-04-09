<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Domain;

use CqrsEsExample\Common\Infrastructure\EventStorage\EventStorageInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @template T of AggregateRoot
 */
final readonly class AggregateRootRepository
{
    /**
     * @param class-string<T> $aggregateRootClassname
     * @throws AggregateException
     */
    public function __construct(
        private string $aggregateRootClassname,
        private EventStorageInterface $eventStorage,
        private MessageBusInterface $eventBus,
    ) {
        if (!class_exists($this->aggregateRootClassname)) {
            throw AggregateException::aggregateClassDoesNotExist($this->aggregateRootClassname);
        }

        if (!is_subclass_of($this->aggregateRootClassname, AggregateRoot::class)) {
            throw AggregateException::aggregateDoesNotSubclassAggregateRoot($this->aggregateRootClassname);
        }
    }

    public function persist(AggregateRoot $aggregateRoot): void
    {
        $releasedEvents = $aggregateRoot->releaseEvents();

        foreach ($releasedEvents as $event) {
            $this->eventBus->dispatch($event);
        }

        $this->eventStorage->persist($aggregateRoot->id(), $releasedEvents);
    }

    /**
     * @return T
     * @throws AggregateException
     */
    public function retrieve(string $aggregateRootId): AggregateRoot
    {
        $events = $this->eventStorage->retrieve($aggregateRootId);

        if ($events->current() === null) {
            throw AggregateException::noEventsForAggregateRootId($aggregateRootId, $this->aggregateRootClassname);
        }

        return $this->aggregateRootClassname::reconstituteFromEvents($aggregateRootId, $events);
    }
}