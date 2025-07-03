<?php

namespace App\Debug\Contracts;

interface CollectorInterface
{
    /**
     * Nom unique du collecteur (ex: 'memory', 'route', 'database')
     */
    public function getName(): string;

    /**
     * Retourne les données collectées sous forme de tableau
     */
    public function collect(): array;
}
