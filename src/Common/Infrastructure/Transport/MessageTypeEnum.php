<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Infrastructure\Transport;

enum MessageTypeEnum: string
{
    case EVENT = 'event';
    case COMMAND = 'command';
    case QUERY = 'query';
}
