<?php

declare(strict_types=1);

namespace Tests\DoctrineDBALAdapter;

use DateTimeImmutable;
use CqrsEsExample\Common\Infrastructure\Symfony\Kernel;
use CqrsEsExample\DoctrineDBALAdapter\DoctrineDBALEventStorage;
use Override;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DoctrineDBALEventStorageTest extends WebTestCase
{
    #[Override]
    protected function setUp(): void
    {
        self::bootKernel();
    }

    #[Override]
    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    /**
     * @test
     * @group functional
     */
    public function it_persists_events(): void
    {
        $eventStorage = static::getContainer()->get(DoctrineDBALEventStorage::class);
        static::assertInstanceOf(DoctrineDBALEventStorage::class, $eventStorage);
        $aggregateRootId = Uuid::uuid4();

        $eventStorage->persist(
            $aggregateRootId->toString(),
            [
                new EventRegistered(
                    new DateTimeImmutable('2024-02-01 14:00:00'),
                    new DateTimeImmutable('2024-02-01 16:00:00'),
                    'Concert',
                    'Dom Omladine',
                )
            ]
        );

        $events = $eventStorage->retrieve($aggregateRootId->toString());
    }
}