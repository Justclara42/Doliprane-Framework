<?php

namespace App\Commands;

use App\Core\View\TemplateEngine;
use App\Core\AssetManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'view:cache',
    description: 'Compile toutes les vues .dtf dans le cache.'
)]
class ViewCacheCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // ✅ On charge manuellement la fonction lang()
        require_once ROOT . '/app/Core/Lang.php';

        $engine = new TemplateEngine();

        // Ajout de variables globales
        $assetManager = new AssetManager();
        $engine->setGlobal('assetManager', $assetManager);

        $engine->compileAllTemplates();

        $output->writeln('<info>✅ Compilation des vues terminée.</info>');
        return Command::SUCCESS;
    }
}
