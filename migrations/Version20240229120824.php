<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229120824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Postgres events table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            create table public.events
            (
                event_id               uuid default gen_random_uuid() not null,
                event_type             varchar                        not null,
                aggregate_root_id      uuid                           not null,
                aggregate_root_version integer                        not null,
                recorded_at            timestamp                      not null,
                event                  json                           not null
            );
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('drop table public.domain_events');
    }
}
