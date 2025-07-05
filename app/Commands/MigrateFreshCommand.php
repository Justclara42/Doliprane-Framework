<?php

namespace App\Commands;

use App\Commands\Base\BaseCommand;
use App\Core\DatabaseManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'migrate:fresh',
    description: 'Supprime toutes les tables de la base de données et exécute les migrations.'
)]
class MigrateFreshCommand extends BaseCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->confirm("Cette action supprimera toutes les tables avant d'exécuter les migrations. Êtes-vous sûr ?", true);

        // Supprime toutes les tables
        DatabaseManager::init();
        $capsule = DatabaseManager::getCapsule();
        $schema = $capsule::schema();
        
        $tables = $schema->getAllTables();
        foreach ($tables as $table) {
            $schema->drop($table);
            $io->text("Table supprimée : $table");
        }
        $io->success('Toutes les tables ont été supprimées.');

        // Exécute les migrations
        $command = $this->getApplication()->find('migrate:run');
        return $command->run($input, $output);
    }
}