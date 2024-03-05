<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Infrastructure\Transport;

use InvalidArgumentException;

final class InvalidMessageException extends InvalidArgumentException
{

}