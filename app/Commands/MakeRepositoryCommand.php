<?php

namespace App\Commands;

use App\Commands\Base\BaseCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'make:repository',
    description: 'Crée un pattern Repository pour accéder aux modèles.'
)]
class MakeRepositoryCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Nom du repository (ex: PostRepository)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $repositoryName = $input->getArgument('name');
        $targetPath = ROOT . '/app/Repositories/' . $repositoryName . '.php';
        $stubPath = ROOT . '/stubs/Repository.stub';

        $this->ensureDirectory(dirname($targetPath), $io, true);

        if (!file_exists($stubPath)) {
            $io->error('Stub de repository manquant.');
            return BaseCommand::FAILURE;
        }

        if (file_exists($targetPath)) {
            $io->warning("Repository déjà existant : $targetPath");
            return BaseCommand::SUCCESS;
        }

        $stub = file_get_contents($stubPath);
        $content = str_replace('{{ repositoryName }}', $repositoryName, $stub);

        file_put_contents($targetPath, $content);
        $io->success("Repository créé : $targetPath");

        return BaseCommand::SUCCESS;
    }
}