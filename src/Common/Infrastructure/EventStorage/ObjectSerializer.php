<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Infrastructure\EventStorage;

use Stringable;

final readonly class ObjectSerializer
{
    public function __construct(
        /**
         * @var array<FieldSerializerInterface>
         */
        private iterable $fieldSerializers = []
    ) {}

    /**
     * If a field is not an object or is a \Stringable, just convert it
     * If we have a serializer registered for a given class, then we should use it
     * If a prop is an object with props, we should recur
     *
     * @return string|array<mixed>
     */
    public function serialize(mixed $value): string|array
    {
        if (!is_object($value) || $value instanceof Stringable) {
            return (string) $value;
        }

        foreach ($this->fieldSerializers as $serializer) {
            if ($serializer->supports($value)) {
                return $serializer->serialize($value);
            }
        }

        foreach (get_object_vars($value) as $key => $var) {
            $result[$key] = $this->serialize($var);
        }

        return $result;
    }

    /**
     * Figure out the kind of the object
     * If it's a constructor, iterate over its arguments, figure out which ones
     * accept plain values, which accept objects that have a registered serializer
     *
     * @param class-string<T of object> $objectClass
     * @param string $serialized
     */
    public function deserialize(string $objectClass, array $serialized): mixed
    {
        $reflection = new \ReflectionClass($objectClass);
        $parameters = $reflection->getConstructor()->getParameters();
        $invocationParameters = [];

        return $reflection->newInstanceArgs($serialized);
    }
}