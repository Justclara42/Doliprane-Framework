<?php

namespace App\Debug;

use App\Debug\Contracts\CollectorInterface;
use App\Debug\Collectors\TimeCollector;
use App\Debug\Collectors\MemoryCollector;
use App\Debug\Collectors\IncludedFilesCollector;
use App\Debug\Collectors\RouteCollector;
use App\Debug\Collectors\DatabaseCollector;
use App\Debug\Collectors\RequestCollector;
use App\Debug\Collectors\PhpInfoCollector;
use App\Debug\Collectors\ControllerCollector;
use App\Debug\Collectors\ModelCollector;
use App\Debug\ErrorLogger;

class DebugManager
{
    /** @var CollectorInterface[] */
    protected array $collectors = [];

    protected array $collectedData = [];

    public function __construct()
    {
        // Enregistrement automatique des collecteurs de base
        $this->addCollector(new TimeCollector());
        $this->addCollector(new MemoryCollector());
        $this->addCollector(new IncludedFilesCollector());
        $this->addCollector(new RouteCollector());
        $this->addCollector(new DatabaseCollector());
        $this->addCollector(new RequestCollector());
        $this->addCollector(new PhpInfoCollector());
        $this->addCollector(new ControllerCollector());
        $this->addCollector(new ModelCollector());
    }

    /**
     * Enregistre un collecteur.
     */
    public function addCollector(CollectorInterface $collector): void
    {
        $name = $collector->getName();
        $this->collectors[$name] = $collector;
    }

    /**
     * Récupère les données de tous les collecteurs.
     */
    public function collectAll(): void
    {
        foreach ($this->collectors as $name => $collector) {
            try {
                $this->collectedData[$name] = $collector->collect();
            } catch (\Throwable $e) {
                $this->collectedData[$name] = ['error' => $e->getMessage()];
            }
        }
        if (isset($GLOBALS['__caught_exception'])) {
            $e = $GLOBALS['__caught_exception'];
            ErrorLogger::logError(
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                $e->getTraceAsString()
            );
        }

        $this->collectedData['errors'] = [
            'count' => count(ErrorLogger::getErrors()),
            'list' => ErrorLogger::getErrors(),
        ];
        $this->collectedData['warnings'] = ErrorLogger::getWarnings();
    }

    /**
     * Alias de collectAll() pour compatibilité et clarté.
     */
    public function collect(): void
    {
        $this->collectAll();
    }

    /**
     * Récupère toutes les données collectées.
     */
    public function getCollectedData(): array
    {
        return $this->collectedData;
    }

    /**
     * Récupère une donnée spécifique par nom de collecteur.
     */
    public function get(string $collectorName): mixed
    {
        return $this->collectedData[$collectorName] ?? null;
    }

    /**
     * Vérifie si un collecteur a été enregistré.
     */
    public function has(string $collectorName): bool
    {
        return isset($this->collectors[$collectorName]);
    }

    /**
     * Réinitialise toutes les données collectées.
     */
    public function reset(): void
    {
        $this->collectedData = [];
    }
}
