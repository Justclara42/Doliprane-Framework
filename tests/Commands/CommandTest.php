<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

it('exécute toutes les commandes avec succès', function () {
    $application = new Application();

    foreach (glob(__DIR__ . '/../../app/Commands/*.php') as $commandFile) {
        $className = 'App\\Commands\\' . basename($commandFile, '.php');

        // Vérifie si la classe n'est pas abstraite
        if (class_exists($className) && !(new ReflectionClass($className))->isAbstract()) {
            $application->add(new $className());
        }
    }

    foreach ($application->all() as $command) {
        if (strpos($command->getName(), 'app:') === 0 || strpos($command->getName(), 'make:') === 0) {
            $tester = new CommandTester($command);

            // Fournir des valeurs pour les arguments requis
            $arguments = [];
            $definition = $command->getDefinition();

            foreach ($definition->getArguments() as $argument) {
                if ($argument->isRequired()) {
                    $arguments[$argument->getName()] = match ($command->getName()) {
                        'make:component' => 'TestComponent',
                        'make:view' => 'example_view',
                        'make:model' => 'ExampleModel',
                        'make:repository' => 'ExampleRepository',
                        'make:controller' => 'ExampleController',
                        'make:database' => 'test_database.sqlite',
                        default => 'test_value',
                    };
                }
            }

            // Exécute la commande avec les arguments requis
            $tester->execute($arguments);

            // Debug: Affichez des informations si le code de statut est différent de 0
            $statusCode = $tester->getStatusCode();
            if ($statusCode !== 0) {
                var_dump('Commande échouée : ' . $command->getName());
                var_dump('Code de statut : ' . $statusCode);
                var_dump('Sortie de la commande : ' . $tester->getDisplay());
            }

            // Vérifie que le code de statut est 0
            expect($statusCode)->toBe(0);
        }
    }
});