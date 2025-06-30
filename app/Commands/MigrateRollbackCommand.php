<?php
namespace App\Commands;

use App\Core\DatabaseManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateRollbackCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('migrate:rollback')
            ->setDescription('Annule toutes les migrations exécutées');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Initialise Capsule
        DatabaseManager::init();

        $migrationsDir = __DIR__ . '/../../database/migrations';

        foreach (array_reverse(glob("$migrationsDir/*.php")) as $file) {
            $migration = require $file;
            if (method_exists($migration, 'down')) {
                $migration->down();
                $output->writeln("<comment>Rollback effectué : " . basename($file) . "</comment>");
            }
        }

        return Command::SUCCESS;
    }
}
