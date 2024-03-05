<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Infrastructure\Transport;

final readonly class Message
{
    /**
     * @param MessageTypeEnum $type
     * @param string $name
     * @param array<string,mixed> $content
     */
    public function __construct(
        public MessageTypeEnum $type,
        public string $name,
        public array $content,
    ) {}
}
