<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Infrastructure\EventStorage;

use DateTimeImmutable;
use DateTimeInterface;
use Override;
use RuntimeException;

final readonly class DateTimeFieldSerializer implements FieldSerializerInterface
{
    public function __construct(
        private string $format = DateTimeInterface::RFC3339_EXTENDED
    ) {}

    #[Override]
    public function supports(mixed $object): bool
    {
        return $object instanceof DateTimeInterface;
    }

    #[Override]
    public function serialize(object $object): string
    {
        assert($object instanceof DateTimeInterface);

        return $object->format(
            $this->format
        );
    }

    #[Override]
    public function deserialize(string $serialized): object
    {
        $result = DateTimeImmutable::createFromFormat(
            $this->format,
            $serialized
        );

        if ($result === false) {
            throw new RuntimeException(sprintf(
                'Cannot deserialize date from %s',
                $serialized
            ));
        }

        return $result;
    }
}