<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeViewCommand extends Command
{
    /**
     * Configure the command options and arguments.
     */
    protected function configure(): void
    {
        $this
            ->setName('make:view')
            ->setDescription('Génère un fichier de vue dans templates/')
            ->addArgument('name', InputArgument::REQUIRED, 'Nom de la vue (ex: users/index)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $path = __DIR__ . '/../../templates/' . $name . '.php';
        $dir = dirname($path);

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if (file_exists($path)) {
            $output->writeln("<comment>La vue existe déjà : templates/$name.php</comment>");
            return Command::SUCCESS;
        }

        $stub = file_get_contents(__DIR__ . '/../../stubs/View.stub');
        $content = str_replace('{{ viewName }}', $name, $stub);

        file_put_contents($path, $content);
        $output->writeln("<info>Vue créée : templates/$name.php</info>");

        return Command::SUCCESS;
    }
}
