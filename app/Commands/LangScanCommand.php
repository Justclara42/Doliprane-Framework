<?php
namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LangScanCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('lang:scan') // ✅ Nom de la commande défini explicitement
            ->setDescription('Scanne les templates pour les balises {% ... %} utilisées pour les traductions.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $directory = __DIR__ . '/../../templates';
        $matches = [];

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );

        foreach ($files as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $content = file_get_contents($file->getPathname());
                if (preg_match_all('/\{\%\s*(.*?)\s*\%\}/', $content, $found)) {
                    $matches = array_merge($matches, $found[1]);
                }
            }
        }

        $unique = array_unique($matches);
        sort($unique);

        if (empty($unique)) {
            $output->writeln("<comment>Aucune balise de traduction trouvée dans les templates.</comment>");
        } else {
            $output->writeln("<info>Clés de traduction détectées :</info>");
            foreach ($unique as $str) {
                $output->writeln(" - {$str}");
            }
        }

        return Command::SUCCESS;
    }
}
