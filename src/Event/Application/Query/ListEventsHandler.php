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
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select('*')
            ->from('events', 'e');

        if ($query->location !== null) {
            $qb->andWhere(
                $qb->expr()->like('e.location', '%'.$query->location.'%')
            );
        }

        if ($query->title !== null) {
            $qb->andWhere(
                $qb->expr()->like('e.title', '%'.$query->title.'%')
            );
        }

        if ($query->fromDate !== null) {
            $qb->andWhere(
                $qb->expr()->gte('e.end')
            );
        }

        return $qb->executeQuery()->fetchAllAssociative();
    }
}