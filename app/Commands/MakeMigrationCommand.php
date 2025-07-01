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

        // Champ ID auto
        $fields[] = ['name' => 'id', 'type' => 'id', 'nullable' => false, 'primary' => true];
        $hasPrimary = true;

        while (true) {
            $fieldName = $helper->ask($input, $output, new Question("📝 Nom du champ : "));
            if (!$fieldName) break;

            $fieldType = $helper->ask($input, $output, new ChoiceQuestion(
                "📦 Type du champ :",
                ['string', 'text', 'integer', 'boolean', 'timestamp', 'date', 'float', 'double', 'email', 'password'],
                0
            ));

            $nullable = $helper->ask($input, $output, new ConfirmationQuestion("❔ Nullable ? (y/N) ", false));
            $primary = false;

            if (!$hasPrimary) {
                $primary = $helper->ask($input, $output, new ConfirmationQuestion("⭐ Clé primaire ? (y/N) ", false));
                if ($primary) $hasPrimary = true;
            }

            // Corrige le type pour la migration si c’est un type spécial
            $originalType = $fieldType;
            if (in_array($fieldType, ['email', 'password'])) {
                $fieldType = 'string';
            }

            $fields[] = [
                'name' => $fieldName,
                'type' => $fieldType,
                'original' => $originalType,
                'nullable' => $nullable,
                'primary' => $primary,
            ];

            $addMore = $helper->ask($input, $output, new ConfirmationQuestion("➕ Ajouter un autre champ ? (y/N) ", false));
            if (!$addMore) break;
        }

        $fields[] = ['name' => 'timestamps', 'type' => 'timestamps', 'nullable' => false, 'primary' => false];

        // Génération du fichier de migration
        $timestamp = date('Y_m_d_His');
        $filename = "{$timestamp}_create_{$tableName}_table.php";
        $path = __DIR__ . "/../../database/migrations/{$filename}";

        $stub = file_get_contents(__DIR__ . '/../../stubs/migration.stub');
        if (!$stub) {
            $output->writeln("<error>❌ Le fichier migration.stub est manquant.</error>");
            return Command::FAILURE;
        }

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
                $fieldsCode .= $line . ";\n";
            }
        }

        $stub = str_replace('{{ table }}', $tableName, $stub);
        $stub = str_replace('{{ fields }}', rtrim($fieldsCode), $stub);
        file_put_contents($path, $stub);
        $output->writeln("<info>✅ Migration générée : database/migrations/{$filename}</info>");

        // Génération du fichier de seeder
        $seederStub = file_get_contents(__DIR__ . '/../../stubs/seeder.stub');
        if (!$seederStub) {
            $output->writeln("<error>❌ Le fichier seeder.stub est manquant.</error>");
            return Command::FAILURE;
        }

        $fieldsSeed = [];
        foreach ($fields as $field) {
            if (in_array($field['name'], ['id', 'timestamps'])) continue;

            $line = match ($field['original']) {
                'email'     => "'{$field['name']}' => \$faker->unique()->safeEmail()",
                'password'  => "'{$field['name']}' => password_hash('password', PASSWORD_DEFAULT)",
                'string', 'text' => "'{$field['name']}' => \$faker->sentence(3)",
                'integer'   => "'{$field['name']}' => \$faker->numberBetween(1, 100)",
                'boolean'   => "'{$field['name']}' => \$faker->boolean()",
                'timestamp','date' => "'{$field['name']}' => \$faker->dateTime()->format('Y-m-d H:i:s')",
                'float','double' => "'{$field['name']}' => \$faker->randomFloat(2, 0, 1000)",
                default     => "'{$field['name']}' => \$faker->word()",
            };
            $fieldsSeed[] = "                $line";
        }

        $seederFields = implode(",\n", $fieldsSeed);
        $seederContent = str_replace(['{{ table }}', '{{ fields }}'], [$tableName, $seederFields], $seederStub);
        $seederPath = __DIR__ . "/../../database/seeders/{$timestamp}_seed_{$tableName}_table.php";
        file_put_contents($seederPath, $seederContent);

        $output->writeln("<info>🌱 Seeder généré : database/seeders/{$timestamp}_seed_{$tableName}_table.php</info>");

        return Command::SUCCESS;
    }
}
