<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Infrastructure\Transport;

final readonly class SchemaDefinition
{
    public function __construct(
        public string $name,
        public string $schemaPath,
        public MessageTypeEnum $kind,
    ) {}
}