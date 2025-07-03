<?php
namespace App\Debug\Collectors;

use App\Debug\Contracts\CollectorInterface;

class RouteCollector implements CollectorInterface
{
    public function collect(): array
    {
        return [
            'method' => $_SERVER['REQUEST_METHOD'] ?? 'N/A',
            'uri' => $_SERVER['REQUEST_URI'] ?? 'N/A',
            // Tu peux lier ici au router si besoin, exemple :
            // 'matched_route' => Router::getMatchedRoute() ?? null
        ];
    }

    public function getName(): string
    {
        return 'route';
    }
}
