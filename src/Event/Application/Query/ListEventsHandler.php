<?php

declare(strict_types=1);

namespace CqrsEsExample\Event\Application\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final readonly class ListEventsHandler
{
    public function __construct(
        private Connection $connection,
    ) {}

    /**
     * @TODO Weak spot in typing here?
     *
     * @return array<array>
     * @throws Exception
     */
    public function __invoke(ListEventsQuery $query): array
    {
        $stmt = $this->connection->prepare('SELECT * FROM events');

        return $stmt->executeQuery()->fetchAllAssociative();
    }
}