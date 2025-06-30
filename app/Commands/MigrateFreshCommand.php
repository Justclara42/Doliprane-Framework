<?php
namespace App\Commands;

use App\Core\DatabaseManager;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateFreshCommand extends Command
{
    /**
     * Configure the command options and description.
     */
    protected function configure(): void
    {
        $this
            ->setName('migrate:fresh')
            ->setDescription('Réinitialise la base de données en annulant puis rejouant toutes les migrations');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        DatabaseManager::init();

        /** @var Application $app */
        $app = $this->getApplication();

        // Rollback
        $rollbackCommand = $app->find('migrate:rollback');
        $rollbackInput = new ArrayInput([]);
        $rollbackCommand->run($rollbackInput, $output);

        // Migrate
        $migrateCommand = $app->find('migrate:run');
        $migrateInput = new ArrayInput([]);
        $migrateCommand->run($migrateInput, $output);

        return Command::SUCCESS;
    }
}
