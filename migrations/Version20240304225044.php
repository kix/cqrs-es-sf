<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304225044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create projection table for events list view';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('create table public.events (
    title      varchar,
    start_time timestamp,
    end_time   timestamp,
    location   varchar
);');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('drop table public.events');
    }
}
