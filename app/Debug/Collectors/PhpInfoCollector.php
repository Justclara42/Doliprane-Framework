<?php

namespace App\Debug\Collectors;

use App\Debug\Contracts\CollectorInterface;

class PhpInfoCollector implements CollectorInterface
{
    public function getName(): string
    {
        return 'phpinfo';
    }

    public function collect(): array
    {
        return [
            'version' => phpversion(),
            'sapi'        => php_sapi_name(),
            'os'          => PHP_OS,
            'extensions'  => get_loaded_extensions()
        ];
    }
}


