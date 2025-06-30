<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeCrudCommand extends Command
{
    /**
     * Configure the command options and arguments.
     */
    protected function configure(): void
    {
        $this
            ->setName('make:crud')
            ->setDescription('Génère un CRUD complet (controller, model, repository, vues)')
            ->addArgument('name', InputArgument::REQUIRED, 'Nom de la ressource (ex: User)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = ucfirst($input->getArgument('name'));
        $viewFolder = strtolower($name) . 's';
        $tableName = $viewFolder;

        // Controller
        $this->generateFile("CrudController.stub", "app/Controllers/{$name}Controller.php", [
            '{{ modelName }}' => $name,
            '{{ viewFolder }}' => $viewFolder
        ]);

        // Model
        $this->generateFile("CrudModel.stub", "app/Models/{$name}.php", [
            '{{ modelName }}' => $name,
            '{{ tableName }}' => $tableName
        ]);

        // Repository
        $this->generateFile("CrudRepository.stub", "app/Repositories/{$name}Repository.php", [
            '{{ modelName }}' => $name
        ]);

        // Views
        $views = [
            'index' => 'CrudView.stub',
            'show' => 'CrudView.stub',
            'create' => 'CrudForm.stub',
            'edit' => 'CrudFormEdit.stub'
        ];

        $viewDir = __DIR__ . "/../../templates/{$viewFolder}";
        if (!is_dir($viewDir)) mkdir($viewDir, 0777, true);

        foreach ($views as $viewName => $stubFile) {
            $this->generateFile($stubFile, "templates/{$viewFolder}/{$viewName}.php", [
                '{{ viewName }}' => "{$viewFolder}/{$viewName}",
                '{{ modelName }}' => $name,
                '{{ routePrefix }}' => $viewFolder
            ]);
        }

        $output->writeln("<info>CRUD complet pour $name généré avec succès !</info>");
        return Command::SUCCESS;
    }

    private function generateFile(string $stubName, string $targetPath, array $replacements): void
    {
        $stubPath = __DIR__ . "/../../stubs/{$stubName}";
        $content = file_get_contents($stubPath);

        foreach ($replacements as $key => $val) {
            $content = str_replace($key, $val, $content);
        }

        file_put_contents(__DIR__ . '/../../' . $targetPath, $content);
    }
}
