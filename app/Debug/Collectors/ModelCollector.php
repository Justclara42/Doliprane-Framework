<?php

namespace App\Debug\Collectors;

use App\Debug\Contracts\CollectorInterface;
use App\Debug\ModelTracker;

class ModelCollector implements CollectorInterface
{
    public function getName(): string
    {
        return 'models';
    }

    public function collect(): array
    {
        return [
            'count' => count(ModelTracker::getTracked()),
            'models' => ModelTracker::getTracked(),
        ];
    }
}
