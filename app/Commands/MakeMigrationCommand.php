<?php

namespace App\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class MakeMigrationCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('make:migration')
            ->setDescription('Crée un fichier de migration avec des champs dynamiques.')
            ->addArgument('name', InputArgument::OPTIONAL, 'Nom de la table');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $tableName = $input->getArgument('name');

        if (!$tableName) {
            $question = new Question("👉 Nom de la table à créer : ");
            $tableName = $helper->ask($input, $output, $question);
        }

        if (!$tableName) {
            $output->writeln("<error>❌ Nom de table invalide.</error>");
            return Command::FAILURE;
        }

        $fields = [];

        // id
        $fields[] = ['name' => 'id', 'type' => 'id', 'nullable' => false, 'primary' => true];
        $hasPrimary = true;

        while (true) {
            $fieldName = $helper->ask($input, $output, new Question("📝 Nom du champ : "));
            if (!$fieldName) break;

            $fieldType = $helper->ask($input, $output, new ChoiceQuestion(
                "📦 Type du champ :",
                ['string', 'text', 'integer', 'boolean', 'timestamp', 'date', 'float', 'double'],
                0
            ));

            $nullable = $helper->ask($input, $output, new ConfirmationQuestion("❔ Nullable ? (y/N) ", false));
            $primary = false;

            if (!$hasPrimary) {
                $primary = $helper->ask($input, $output, new ConfirmationQuestion("⭐ Clé primaire ? (y/N) ", false));
                if ($primary) $hasPrimary = true;
            }

            $fields[] = [
                'name' => $fieldName,
                'type' => $fieldType,
                'nullable' => $nullable,
                'primary' => $primary,
            ];

            $addMore = $helper->ask($input, $output, new ConfirmationQuestion("➕ Ajouter un autre champ ? (y/N) ", false));
            if (!$addMore) break;
        }

        // timestamps
        $fields[] = ['name' => 'timestamps', 'type' => 'timestamps', 'nullable' => false, 'primary' => false];

        // génération fichier
        $timestamp = date('Y_m_d_His');
        $filename = "{$timestamp}_create_{$tableName}_table.php";
        $path = __DIR__ . "/../../database/migrations/{$filename}";

        $stubPath = __DIR__ . '/../../stubs/migration.stub';
        if (!file_exists($stubPath)) {
            $output->writeln("<error>❌ Le fichier stub n'existe pas.</error>");
            return Command::FAILURE;
        }

        $stub = file_get_contents($stubPath);

        $fieldsCode = '';
        foreach ($fields as $field) {
            if ($field['type'] === 'id') {
                $fieldsCode .= "            \$table->id();\n";
            } elseif ($field['type'] === 'timestamps') {
                $fieldsCode .= "            \$table->timestamps();\n";
            } else {
                $line = "            \$table->{$field['type']}('{$field['name']}')";
                if ($field['nullable']) $line .= "->nullable()";
                if ($field['primary']) $line .= "->primary()";
                $line .= ";";
                $fieldsCode .= $line . "\n";
            }
        }

        $stub = str_replace('{{ table }}', $tableName, $stub);
        $stub = str_replace('{{ fields }}', rtrim($fieldsCode), $stub);

        file_put_contents($path, $stub);

        $output->writeln("<info>✅ Migration générée : database/migrations/{$filename}</info>");

        return Command::SUCCESS;
    }
}
