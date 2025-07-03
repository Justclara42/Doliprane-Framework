<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeComponentCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('make:component')
            ->setDescription('Crée un composant avec sa vue')
            ->addArgument('name', InputArgument::REQUIRED, 'Nom du composant (ex: Alert ou AlertComponent)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rawName = $input->getArgument('name');

        // On extrait le nom sans "Component" et on force "Component" à la fin du nom de fichier et de classe
        $baseName = ucfirst(preg_replace('/Component$/', '', $rawName)); // Alert
        $className = $baseName . 'Component'; // AlertComponent
        $fileName = $className . '.php'; // AlertComponent.php
        $viewName = strtolower($baseName); // alert

        $componentPath = ROOT . "/app/Components/{$fileName}";
        $viewPath = ROOT . "/templates/components/{$viewName}.dtf";

        if (!file_exists(dirname($componentPath))) mkdir(dirname($componentPath), 0775, true);
        if (!file_exists(dirname($viewPath))) mkdir(dirname($viewPath), 0775, true);

        // Génération du fichier PHP du composant
        $stub = <<<PHP
<?php

namespace App\Components;

use App\Core\Component;

class $className extends Component
{
    public function __construct(array \$data = [])
    {
        parent::__construct(\$data);
        \$this->view = 'components.$viewName';
    }
}
PHP;

        file_put_contents($componentPath, $stub);

        // Génération de la vue associée si elle n'existe pas
        if (!file_exists($viewPath)) {
            file_put_contents($viewPath, <<<HTML
<div class="alert alert-{{ \$type ?? 'info' }}">
    {{ \$message ?? '' }} {{ \$slot ?? '' }}
</div>
HTML);
        }

        $output->writeln("<info>✅ Composant créé : $componentPath</info>");
        $output->writeln("<info>🖼️  Vue associée : $viewPath</info>");

        return Command::SUCCESS;
    }
}
