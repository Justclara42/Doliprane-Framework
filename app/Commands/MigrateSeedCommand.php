<?php
namespace App\Commands;

use App\Core\DatabaseManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateSeedCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('migrate:seed')
            ->setDescription('Insère des données de test à partir des seeders');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        DatabaseManager::init();

        $seedersDir = __DIR__ . '/../../database/seeders';

        foreach (glob("$seedersDir/*.php") as $file) {
            $seeder = require $file;
            if (method_exists($seeder, 'run')) {
                $seeder->run();
                $output->writeln("<info>✅ Données insérées via : " . basename($file) . "</info>");
            }
        }

        return Command::SUCCESS;
    }
}
