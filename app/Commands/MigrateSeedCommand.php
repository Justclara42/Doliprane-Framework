<?php

namespace App\Commands;

use App\Commands\Base\BaseCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'migrate:seed',
    description: 'Remplit la base de données avec des données factices via des seeders.'
)]
class MigrateSeedCommand extends BaseCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $seederDir = ROOT . '/database/seeders';
        $this->ensureDirectory($seederDir, $io);

        $seederFiles = glob("$seederDir/*.php");
        if (empty($seederFiles)) {
            $io->warning("Aucun fichier seeder trouvé dans $seederDir.");
            return BaseCommand::SUCCESS;
        }

        foreach ($seederFiles as $file) {
            $seederClass = basename($file, '.php');

            require_once $file;

            if (class_exists($seederClass)) {
                $io->text("Exécution du seeder : $seederClass");
                (new $seederClass())->run();
            } else {
                $io->error("Le seeder $seederClass n'existe pas.");
            }
        }

        $io->success('Les seeders ont été exécutés avec succès.');
        return BaseCommand::SUCCESS;
    }
}