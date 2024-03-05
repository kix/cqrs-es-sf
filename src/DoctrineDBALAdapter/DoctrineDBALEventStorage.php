<?php

declare(strict_types=1);

namespace CqrsEsExample\DoctrineDBALAdapter;

use Doctrine\DBAL\Connection;
use CqrsEsExample\Common\Infrastructure\EventStorage\EventStorageInterface;
use Generator;
use Override;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Serializer\SerializerInterface;

final class DoctrineDBALEventStorage implements EventStorageInterface
{
    public function __construct(
        private readonly Connection          $connection,
        private readonly SerializerInterface $serializer,
        private readonly LoggerInterface     $logger = new NullLogger(),
    ) { }

    /**
     * @param string $aggregateRootId
     * @param object[] $events
     * @throws \Doctrine\DBAL\Exception
     * @throws \JsonException
     */
    #[Override]
    public function persist(string $aggregateRootId, array $events): void
    {
        $stmt = $this->connection->prepare(
            'insert into public.domain_events 
                    (event_type, aggregate_root_id, aggregate_root_version, event, recorded_at) 
                values (
                    :eventType,
                    :aggregateRootId,
                    1,
                    :event,
                    NOW()
                )'
        );

        // TODO: Disallow anonymous classes since those cannot be reinstantiated?

        foreach ($events as $event) {
            $serialized = $this->serializer->serialize($event, 'json');

            $this->logger->debug("Persisting event {class} for aggregate {aggregateRootId}:\n{event}", [
                'class' => $event::class,
                'aggregateRootId' => $aggregateRootId,
                'event' => $serialized,
            ]);

            $stmt->executeQuery([
                'eventType' => $event::class,
                'aggregateRootId' => (string) $aggregateRootId,
                'event' => $serialized,
            ]);
        }
    }

    #[Override]
    public function retrieve(string $aggregateRootId): Generator
    {
        $stmt = $this->connection->executeQuery(
            'select event_type, event from public.domain_events where aggregate_root_id = :id',
            [
                'id' => (string) $aggregateRootId
            ]
        );

        while ($row = $stmt->fetchAssociative()) {
            assert(is_string($row['event_type']));

            yield $this->serializer->deserialize(
                $row['event'],
                $row['event_type'],
                'json',
            );
        }
    }
}