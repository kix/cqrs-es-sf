<?php

declare(strict_types=1);

namespace CqrsEsExample\Event\Application\Projection;

use CqrsEsExample\Event\Application\Command\RegisterEventCalendarCommand;
use CqrsEsExample\Event\Domain\Event\EventCalendarRegistered;

class RegisterCalendarProjector
{
    public function __invoke(EventCalendarRegistered $event): void
    {
        // TODO: Implement __invoke() method.
    }
}