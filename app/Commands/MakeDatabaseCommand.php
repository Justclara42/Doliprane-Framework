<?php

namespace App\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'make:database',
    description: 'Crée un fichier SQLite dans /database et met à jour le .env.'
)]
class MakeDatabaseCommand extends Command
{
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::OPTIONAL, 'Nom du fichier SQLite', 'database.sqlite');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dbName = $input->getArgument('name');
        $envPath = ROOT . '/.env';
        $databaseDir = ROOT . '/database';
        $dbPath = "$databaseDir/$dbName";

        if (!is_dir($databaseDir)) {
            mkdir($databaseDir, 0777, true);
        }

        if (!file_exists($dbPath)) {
            touch($dbPath);
            $output->writeln("✅ Fichier SQLite créé : <info>$dbPath</info>");
        } else {
            $output->writeln("ℹ️ Fichier déjà existant : <comment>$dbPath</comment>");
        }

        // Mise à jour .env
        $env = file_exists($envPath) ? file_get_contents($envPath) : '';

        $env = preg_replace('/^DB_DATABASE=.*$/m', "DB_DATABASE={$dbName}", $env);
        $env = preg_replace('/^DB_CONNECTION=.*$/m', "DB_CONNECTION=sqlite", $env);

        if (!str_contains($env, "DB_DATABASE=")) {
            $env .= "\nDB_DATABASE={$dbName}";
        }

        if (!str_contains($env, "DB_CONNECTION=")) {
            $env .= "\nDB_CONNECTION=sqlite";
        }

        file_put_contents($envPath, $env);
        $output->writeln("✅ .env mis à jour avec : <info>DB_CONNECTION=sqlite</info> et <info>DB_DATABASE={$dbName}</info>");

        return Command::SUCCESS;
    }
}
