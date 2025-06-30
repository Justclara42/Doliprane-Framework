<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeModelCommand extends Command
{
    /**
     * Configure the command options and arguments.
     */
    protected function configure(): void
    {
        $this
            ->setName('make:model')
            ->setDescription('Crée un modèle Eloquent dans app/Models.')
            ->addArgument('name', InputArgument::REQUIRED, 'Nom du modèle (ex: Post)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $className = $input->getArgument('name');
        $tableName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className)) . 's';

        $stubPath = __DIR__ . '/../../stubs/Model.stub';
        $targetPath = 'app/Models/' . $className . '.php';

        if (!file_exists($stubPath)) {
            $output->writeln('<error>Stub de modèle manquant.</error>');
            return Command::FAILURE;
        }

        if (file_exists($targetPath)) {
            $output->writeln('<comment>Modèle déjà existant.</comment>');
        } else {
            $stub = file_get_contents($stubPath);
            $stub = str_replace(
                ['{{ className }}', '{{ tableName }}'],
                [$className, $tableName],
                $stub
            );
            file_put_contents($targetPath, $stub);
            $output->writeln("<info>Modèle créé : $targetPath</info>");
        }

        return Command::SUCCESS;
    }
}
