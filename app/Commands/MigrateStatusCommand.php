<?php

namespace App\Commands;

use App\Commands\Base\BaseCommand;
use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'migrate:status',
    description: 'Affiche l’état des migrations exécutées.'
)]
class MigrateStatusCommand extends BaseCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        DatabaseManager::init();
        $schema = Capsule::schema();
        $migrationTable = config('database.migrations_table', 'migrations');

        if (!$schema->hasTable($migrationTable)) {
            $io->warning("La table des migrations ($migrationTable) n'existe pas.");
            return BaseCommand::SUCCESS;
        }

        $migrations = Capsule::table($migrationTable)->orderBy('batch', 'asc')->get();

        if ($migrations->isEmpty()) {
            $io->text("Aucune migration exécutée.");
        } else {
            $rows = [];
            foreach ($migrations as $migration) {
                $rows[] = [$migration->id, $migration->migration, $migration->batch];
            }
            $io->table(['ID', 'Migration', 'Batch'], $rows);
        }

        return BaseCommand::SUCCESS;
    }
}