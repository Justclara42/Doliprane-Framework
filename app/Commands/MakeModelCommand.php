<?php

namespace App\Commands;

use App\Commands\Base\BaseCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'make:model',
    description: 'Crée un modèle Eloquent dans app/Models.'
)]
class MakeModelCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Nom du modèle (ex: Post)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $className = $input->getArgument('name');
        $tableName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className)) . 's';

        $stubPath = ROOT . '/stubs/Model.stub';
        $targetPath = ROOT . '/app/Models/' . $className . '.php';

        if (!file_exists($stubPath)) {
            $io->error('Stub de modèle manquant.');
            return BaseCommand::FAILURE;
        }

        if (file_exists($targetPath)) {
            $io->warning("Modèle déjà existant : $targetPath");
            return BaseCommand::SUCCESS;
        }

        $stub = file_get_contents($stubPath);
        $content = str_replace(
            ['{{ className }}', '{{ tableName }}'],
            [$className, $tableName],
            $stub
        );

        file_put_contents($targetPath, $content);
        $io->success("Modèle créé : $targetPath");

        return BaseCommand::SUCCESS;
    }
}