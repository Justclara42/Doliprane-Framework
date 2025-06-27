<?php
namespace App\Core;

class View {
    public static function render(string $template, array $data = []): void {
        extract($data);
        include __DIR__ . '/../../templates/' . $template . '.php';
    }

    public static function layout(string $layout, string $template, array $data = []): void {
        extract($data);
        $templateFile = __DIR__ . '/../../templates/' . $template . '.php';
        include __DIR__ . '/../../templates/layouts/' . $layout . '.php';
    }
}
