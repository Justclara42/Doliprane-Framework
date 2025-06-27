<?php
namespace App\Core;

abstract class Controller {
    public function view(string $view, array $data = []) {
        View::render($view, $data);
    }
}
