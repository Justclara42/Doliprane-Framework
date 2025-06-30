<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeRepositoryCommand extends Command
{
    /**
     * Configure the command options and arguments.
     */
    protected function configure(): void
    {
        $this
            ->setName('make:repository')
            ->setDescription('Crée un repository dans app/Repositories.')
            ->addArgument('name', InputArgument::REQUIRED, 'Nom du repository (ex: PostRepository)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $className = $input->getArgument('name');

        $stubPath = __DIR__ . '/../../stubs/Repository.stub';
        $targetPath = 'app/Repositories/' . $className . '.php';

        if (!file_exists($stubPath)) {
            $output->writeln('<error>Stub de repository manquant.</error>');
            return Command::FAILURE;
        }

        if (file_exists($targetPath)) {
            $output->writeln('<comment>Repository déjà existant.</comment>');
        } else {
            $stub = file_get_contents($stubPath);
            $stub = str_replace('{{ className }}', $className, $stub);
            file_put_contents($targetPath, $stub);
            $output->writeln("<info>Repository créé : $targetPath</info>");
        }

        return Command::SUCCESS;
    }
}
