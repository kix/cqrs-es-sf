<?php

declare(strict_types=1);

namespace Common\Infrastructure\EventStorage;

use DateTimeImmutable;
use CqrsEsExample\Common\Infrastructure\EventStorage\DateTimeFieldSerializer;
use CqrsEsExample\Common\Infrastructure\EventStorage\ObjectSerializer;
use Override;
use PHPUnit\Framework\TestCase;
use stdClass;

final class ObjectSerializerTest extends TestCase
{
    private ObjectSerializer $eventSerializer;

    #[Override]
    protected function setUp(): void
    {
        $this->eventSerializer = new ObjectSerializer([
            new DateTimeFieldSerializer()
        ]);
    }

    /**
     * @test
     */
    public function it_serializes_plain_objects(): void
    {
        $event = new readonly class ('John', 'Smith') {
            public function __construct(
                public string $firstName,
                public string $lastName
            ) { }
        };

        static::assertEquals(
            [
                'firstName' => 'John',
                'lastName' => 'Smith',
            ],
            $this->eventSerializer->serialize($event)
        );
    }

    /**
     * @test
     */
    public function it_serializes_nested_objects(): void
    {
        $latLng = new stdClass();
        $latLng->lat = 14.12321;
        $latLng->lng = 43.12132;

        $location = new stdClass();
        $location->locationName = 'London';
        $location->coordinates = $latLng;

        $event = new readonly class ('Concert', $location) {
            public function __construct(
                public string $eventName,
                public object $location
            ) { }
        };

        $serialized = $this->eventSerializer->serialize($event);

        static::assertEquals(
            [
                'eventName' => 'Concert',
                'location' =>
                    [
                        'locationName' => 'London',
                        'coordinates' =>
                            [
                                'lat' => '14.12321',
                                'lng' => '43.12132',
                            ],
                    ],
            ],
            $serialized
        );
    }

    /**
     * @test
     */
    public function it_serializes_nested_objects_with_nonstring_props(): void
    {
        $event = new readonly class ('Concert', new DateTimeImmutable('2020-01-01 12:00:00')) {
            public function __construct(
                public string $eventName,
                public DateTimeImmutable $occursAt
            ) { }
        };

        $serialized = $this->eventSerializer->serialize($event);

        static::assertEquals(
            [
                'eventName' => 'Concert',
                'occursAt' => '2020-01-01T12:00:00.000+00:00',
            ],
            $serialized
        );
    }

    /**
     * @test
     */
    public function it_deserializes_plain_objects(): void
    {
        $event = new readonly class ('Concert', new DateTimeImmutable('2020-01-01 12:00:00')) {
            public function __construct(
                public string $eventName,
                public DateTimeImmutable $occursAt
            ) { }
        };

        static::assertEquals(
            $event,
            $this->eventSerializer->deserialize(
                $event::class,
                [
                    'eventName' => 'Concert',
                    'occursAt' => '2020-01-01T12:00:00.000+00:00',
                ],
            )
        );
    }
}