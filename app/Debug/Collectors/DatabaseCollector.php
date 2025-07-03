<?php

namespace App\Debug\Collectors;

use App\Debug\Contracts\CollectorInterface;
use App\Core\DatabaseManager;

class DatabaseCollector implements CollectorInterface
{
    public function collect(): array
    {
        $queries = DatabaseManager::getLoggedQueries();

        // Calcul total du temps d'exécution de toutes les requêtes
        $totalTime = array_reduce($queries, function ($carry, $query) {
            return $carry + ($query['duration'] ?? 0);
        }, 0);

        return [
            'query_count' => count($queries),
            'total_time' => $totalTime,
            'queries' => $queries,
        ];
    }

    public function getName(): string
    {
        return 'database';
    }
}
