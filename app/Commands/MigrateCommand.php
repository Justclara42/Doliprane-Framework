<?php
namespace App\Commands;

use App\Core\DatabaseManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('migrate:run')->setDescription('Exécute toutes les migrations');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Initialise Capsule
        DatabaseManager::init();

        $migrationsDir = __DIR__ . '/../../database/migrations';

        foreach (glob("$migrationsDir/*.php") as $file) {
            $migration = require $file;
            if (method_exists($migration, 'up')) {
                $migration->up();
                $output->writeln("<info>Migration exécutée : " . basename($file) . "</info>");
            }
        }

        return Command::SUCCESS;
    }
}
