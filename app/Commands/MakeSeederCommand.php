<?php

namespace App\Commands;

use App\Commands\Base\BaseCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'make:seeder',
    description: 'Crée un fichier seeder dans /database/seeders.'
)]
class MakeSeederCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Nom du seeder (ex: UsersTableSeeder)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $input->getArgument('name');
        $filePath = ROOT . "/database/seeders/{$name}.php";

        $this->ensureDirectory(dirname($filePath), $io, true);

        if (file_exists($filePath)) {
            $io->warning("Le fichier seeder existe déjà : $filePath");
            return BaseCommand::SUCCESS;
        }

        $stubPath = ROOT . '/stubs/Seeder.stub';

        if (!file_exists($stubPath)) {
            $io->error("Le stub de seeder est introuvable.");
            return BaseCommand::FAILURE;
        }

        $stub = file_get_contents($stubPath);
        $stub = str_replace('{{ className }}', $name, $stub);

        file_put_contents($filePath, $stub);
        $io->success("Seeder créé : $filePath");

        return BaseCommand::SUCCESS;
    }
}