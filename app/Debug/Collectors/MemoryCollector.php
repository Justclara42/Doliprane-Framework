<?php

namespace App\Debug\Collectors;

use App\Debug\Contracts\CollectorInterface;

class MemoryCollector implements CollectorInterface
{
    public function getName(): string
    {
        return 'memory';
    }

    public function collect(): array
    {
        return [
            'used' => memory_get_usage(true),
            'peak'  => memory_get_peak_usage(true)
        ];
    }
}
