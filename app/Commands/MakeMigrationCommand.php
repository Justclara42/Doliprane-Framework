<?php

namespace App\Commands;

use App\Commands\Base\BaseCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'make:migration',
    description: 'Crée un fichier de migration dans /database/migrations.'
)]
class MakeMigrationCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Nom de la migration (ex: create_users_table)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $input->getArgument('name');
        $datePrefix = date('Y_m_d_His');
        $fileName = "{$datePrefix}_{$name}.php";
        $filePath = ROOT . "/database/migrations/{$fileName}";

        $this->ensureDirectory(dirname($filePath), $io, true);

        if (file_exists($filePath)) {
            $io->warning("Le fichier de migration existe déjà : $filePath");
            return BaseCommand::SUCCESS;
        }

        $stubPath = ROOT . '/stubs/Migration.stub';

        if (!file_exists($stubPath)) {
            $io->error("Le stub de migration est introuvable.");
            return BaseCommand::FAILURE;
        }

        $stub = file_get_contents($stubPath);
        $stub = str_replace('{{ className }}', ucfirst($name), $stub);

        file_put_contents($filePath, $stub);
        $io->success("Migration créée : $filePath");

        return BaseCommand::SUCCESS;
    }
}