<?php

namespace App\Commands;

use App\Commands\Base\BaseCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'make:view',
    description: 'Génère un fichier de vue dans templates/.'
)]
class MakeViewCommand extends BaseCommand
{
    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Nom de la vue (ex: users/index)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $name = $input->getArgument('name');
        $path = ROOT . '/templates/' . $name . '.php';
        $dir = dirname($path);

        // Vérification ou création du répertoire
        $this->ensureDirectory($dir, $io, true);

        if (file_exists($path)) {
            $io->warning("La vue existe déjà : templates/$name.php");
            return BaseCommand::SUCCESS;
        }

        $stub = file_get_contents(ROOT . '/stubs/View.stub');
        $content = str_replace('{{ viewName }}', $name, $stub);

        file_put_contents($path, $content);
        $io->success("Vue créée : templates/$name.php");

        return BaseCommand::SUCCESS;
    }
}