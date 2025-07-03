<?php

namespace App\Debug\Collectors;

use App\Debug\Contracts\CollectorInterface;

class TimeCollector implements CollectorInterface
{
    protected float $startTime;

    public function __construct()
    {
        $this->startTime = $_SERVER['REQUEST_TIME_FLOAT'] ?? microtime(true);
    }

    public function getName(): string
    {
        return 'time';
    }

    public function collect(): array
    {
        return [
            'start' => $this->startTime,
            'end'   => microtime(true),
            'duration_ms' => round((microtime(true) - $this->startTime) * 1000, 2)
        ];
    }
}
