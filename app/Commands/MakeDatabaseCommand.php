<?php

namespace App\Commands;

use App\Commands\Base\BaseCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'make:database',
    description: 'Crée un fichier SQLite dans /database et met à jour le .env.'
)]
class MakeDatabaseCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::OPTIONAL, 'Nom du fichier SQLite', 'database.sqlite');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $dbName = $input->getArgument('name');
        $databaseDir = ROOT . '/database';
        $dbPath = "$databaseDir/$dbName";

        // Vérification ou création du répertoire
        $this->ensureDirectory($databaseDir, $io, true);

        if (!file_exists($dbPath)) {
            touch($dbPath);
            $io->success("Fichier SQLite créé : $dbPath");
        } else {
            $io->warning("Fichier SQLite déjà existant : $dbPath");
        }

        // Mise à jour de .env
        $envPath = ROOT . '/.env';
        $env = file_exists($envPath) ? file_get_contents($envPath) : '';
        $env = preg_replace('/^DB_DATABASE=.*$/m', "DB_DATABASE={$dbName}", $env);
        file_put_contents($envPath, $env);

        $io->success("Le fichier .env a été mis à jour avec DB_DATABASE={$dbName}.");

        return BaseCommand::SUCCESS;
    }
}