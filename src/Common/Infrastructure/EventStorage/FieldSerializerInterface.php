<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Infrastructure\EventStorage;

interface FieldSerializerInterface
{
    /**
     * @TODO Maybe use a `getClass` instead of the registry approach?
     */
    public function supports(object $object): bool;

    public function serialize(object $object): string;

    public function deserialize(string $serialized): object;
}