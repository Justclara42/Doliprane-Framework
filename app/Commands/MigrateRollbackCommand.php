<?php

namespace App\Commands;

use App\Commands\Base\BaseCommand;
use App\Core\DatabaseManager;
use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'migrate:rollback',
    description: 'Annule la dernière migration exécutée.'
)]
class MigrateRollbackCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this->addArgument('steps', InputArgument::OPTIONAL, 'Nombre de migrations à annuler', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $steps = (int) $input->getArgument('steps');

        // Initialiser la connexion à la base
        DatabaseManager::init();

        $migrationTable = config('database.migrations_table', 'migrations');
        $migrations = Capsule::table($migrationTable)
            ->orderBy('batch', 'desc')
            ->limit($steps)
            ->get();

        if ($migrations->isEmpty()) {
            $io->warning('Aucune migration à annuler.');
            return BaseCommand::SUCCESS;
        }

        foreach ($migrations as $migration) {
            $io->section("Annulation de la migration : {$migration->migration}");
            $migrationFile = ROOT . "/database/migrations/{$migration->migration}.php";
            if (file_exists($migrationFile)) {
                $instance = require $migrationFile;
                if (method_exists($instance, 'down')) {
                    $instance->down();
                    Capsule::table($migrationTable)->where('id', $migration->id)->delete();
                    $io->success("Migration annulée : {$migration->migration}");
                } else {
                    $io->error("La méthode down() est manquante pour : {$migration->migration}");
                }
            } else {
                $io->error("Fichier de migration introuvable : {$migrationFile}");
            }
        }

        return BaseCommand::SUCCESS;
    }
}
