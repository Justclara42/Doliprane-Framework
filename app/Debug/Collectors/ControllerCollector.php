<?php

namespace App\Debug\Collectors;

use App\Debug\Contracts\CollectorInterface;

class ControllerCollector implements CollectorInterface
{
    public function getName(): string
    {
        return 'controller';
    }

    public function collect(): array
    {
        return [
            'controller' => $_REQUEST['__controller_called'] ?? 'Non défini',
        ];
    }
}
