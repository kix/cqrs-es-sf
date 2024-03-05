<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Infrastructure\EventStorage;

use Generator;
use Stringable;

interface EventStorageInterface
{
    /**
     * @param string $aggregateRootId
     * @param array<object> $events
     * @return void
     */
    public function persist(string $aggregateRootId, array $events): void;

    public function retrieve(string $aggregateRootId): Generator;
}