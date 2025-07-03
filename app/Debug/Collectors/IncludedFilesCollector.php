<?php

namespace App\Debug\Collectors;

use App\Debug\Contracts\CollectorInterface;

class IncludedFilesCollector implements CollectorInterface
{
    public function collect(): array
    {
        return [
            'count' => count(get_included_files()),
            'files' => get_included_files(),
        ];
    }

    public function getName(): string
    {
        return 'included_files';
    }
}
