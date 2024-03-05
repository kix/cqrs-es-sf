<?php

declare(strict_types=1);

namespace CqrsEsExample\Common\Infrastructure\UI\Console;

use CqrsEsExample\Common\Infrastructure\Transport\SchemaRegistry;
use Override;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DumpSchemasCommand extends Command
{
    public const NAME = 'events:dump-schemas';

    public function __construct(
        private SchemaRegistry $schemaRegistry,
    ) {
        parent::__construct(self::NAME);
    }

    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->schemaRegistry->schemas as $schema) {
            $output->writeln(sprintf(
                '<info>%s (%s)</info>: %s',
                $schema->name,
                $schema->kind->value,
                $schema->schemaPath
            ));
        }

        return 0;
    }
}