<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Infrastructure\Transport;

use RuntimeException;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Finder\Finder;

final readonly class SchemaRegistry
{
    /**
     * @var array<string,SchemaDefinition>
     */
    public array $schemas;

    public function __construct()
    {
        $schemas = array_map(
            static fn (SplFileInfo $fileInfo): SchemaDefinition => new SchemaDefinition(
                substr($fileInfo->getRelativePath().'/'.$fileInfo->getFilename(), 0, -12),
                $fileInfo->getRealPath(),
                MessageTypeEnum::from(
                    array_reverse(array_filter(explode('/', $fileInfo->getRelativePath())))[0]
                )
            ),
            iterator_to_array(
                Finder::create()
                    ->files()
                    ->name('*.schema.json')
                    ->in(__DIR__.'/../../../../schemas')
                    ->getIterator()
            )
        );

        $this->schemas = array_combine(
            array_map(static fn(SchemaDefinition $defn) => $defn->name, $schemas),
            $schemas
        );
    }

    public function getSchemaRealpath(string $eventName): string
    {
        if (!array_key_exists($eventName, $this->schemas)) {
             throw new RuntimeException(sprintf(
                 'Schema for event `%s` does not exist',
                 $eventName
             ));
        }

        return $this->schemas[$eventName]->schemaPath;
    }
}
