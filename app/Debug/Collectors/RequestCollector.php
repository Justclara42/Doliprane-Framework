<?php

namespace App\Debug\Collectors;

use App\Debug\Contracts\CollectorInterface;

class RequestCollector implements CollectorInterface
{
    public function getName(): string
    {
        return 'request';
    }

    public function collect(): array
    {
        return [
            'method'  => $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN',
            'uri'     => $_SERVER['REQUEST_URI'] ?? '',
            'params'  => $_REQUEST,
            'cookies' => $_COOKIE,
            'session' => $_SESSION ?? [],
            'headers' => getallheaders()
        ];
    }
}
