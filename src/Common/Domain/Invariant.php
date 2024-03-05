<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Domain;

use ReflectionClass;

abstract class Invariant
{
    final public function __construct() {}

    public static function getDescription(): string
    {
        return static::class;
    }

    /**
     * @param array<object> $events
     * @return static
     */
    public static function reconstituteFromEvents(array $events): static
    {
        $instance = new static();

        foreach ($events as $event) {
            $eventClass = (new ReflectionClass($event))->getShortName();
            $applicatorMethodName = 'apply'.$eventClass;

            if (!method_exists($instance, $applicatorMethodName)) {
                continue;
            }

            $instance->$applicatorMethodName($event);
        }

        return $instance;
    }
}